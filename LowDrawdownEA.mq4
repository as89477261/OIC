//+------------------------------------------------------------------+
//|                                              LowDrawdownEA.mq4    |
//|                        High Precision - Low Drawdown EA           |
//|                        Multiple Indicator Confirmation            |
//+------------------------------------------------------------------+
#property copyright "Low Drawdown Trading System"
#property link      ""
#property version   "1.00"
#property strict

//--- Input Parameters
input string  Section1 = "=== Risk Management ===";
input double  RiskPercent = 1.0;        // Risk per trade (% of balance)
input double  MaxDrawdown = 15.0;       // Maximum allowed drawdown (%)
input double  RiskRewardRatio = 2.0;    // Risk:Reward Ratio (1:X)

input string  Section2 = "=== MACD Settings ===";
input int     MACD_Fast = 12;           // MACD Fast EMA
input int     MACD_Slow = 26;           // MACD Slow EMA
input int     MACD_Signal = 9;          // MACD Signal Period

input string  Section3 = "=== Bollinger Bands ===";
input int     BB_Period = 20;           // Bollinger Bands Period
input double  BB_Deviation = 2.0;       // Bollinger Bands Deviation
input ENUM_APPLIED_PRICE BB_AppliedPrice = PRICE_CLOSE;

input string  Section4 = "=== RSI Settings ===";
input int     RSI_Period = 14;          // RSI Period
input double  RSI_Oversold = 30.0;      // RSI Oversold Level
input double  RSI_Overbought = 70.0;    // RSI Overbought Level

input string  Section5 = "=== ATR Settings ===";
input int     ATR_Period = 14;          // ATR Period
input double  ATR_SL_Multiplier = 2.0;  // ATR Stop Loss Multiplier
input double  ATR_MaxVolatility = 3.0;  // Max ATR for entry (filter high volatility)

input string  Section6 = "=== Moving Average ===";
input int     MA_Period = 200;          // Main Trend MA Period
input ENUM_MA_METHOD MA_Method = MODE_EMA;

input string  Section7 = "=== Trading Settings ===";
input int     MagicNumber = 12345;      // Magic Number
input int     Slippage = 3;             // Slippage
input bool    UseTrailingStop = true;   // Use Trailing Stop
input double  TrailingStart = 20.0;     // Trailing Start (pips)
input double  TrailingStop = 15.0;      // Trailing Stop (pips)
input int     MinBarsBetweenTrades = 10;// Minimum bars between trades

//--- Global Variables
datetime LastTradeTime = 0;
int LastTradeBar = 0;
double InitialBalance = 0;

//+------------------------------------------------------------------+
//| Expert initialization function                                     |
//+------------------------------------------------------------------+
int OnInit()
{
   InitialBalance = AccountBalance();
   Print("Low Drawdown EA Initialized");
   Print("Initial Balance: ", InitialBalance);
   Print("Risk Per Trade: ", RiskPercent, "%");
   return(INIT_SUCCEEDED);
}

//+------------------------------------------------------------------+
//| Expert deinitialization function                                   |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
{
   Print("EA Stopped. Final Balance: ", AccountBalance());
}

//+------------------------------------------------------------------+
//| Expert tick function                                               |
//+------------------------------------------------------------------+
void OnTick()
{
   // Check if new bar formed
   if(Bars < 100) return;

   // Check drawdown limit
   if(!CheckDrawdownLimit()) return;

   // Manage existing positions
   if(HasOpenPosition())
   {
      if(UseTrailingStop)
         ManageTrailingStop();
      return;
   }

   // Check if enough bars passed since last trade
   if(Bars - LastTradeBar < MinBarsBetweenTrades) return;

   // Analyze market and look for trading signals
   int signal = AnalyzeMarket();

   if(signal == 1) // Buy Signal
   {
      OpenBuyPosition();
   }
   else if(signal == -1) // Sell Signal
   {
      OpenSellPosition();
   }
}

//+------------------------------------------------------------------+
//| Analyze Market with Multiple Indicators                           |
//+------------------------------------------------------------------+
int AnalyzeMarket()
{
   // Get current indicators values
   double macd_main = iMACD(NULL, 0, MACD_Fast, MACD_Slow, MACD_Signal, PRICE_CLOSE, MODE_MAIN, 1);
   double macd_main_prev = iMACD(NULL, 0, MACD_Fast, MACD_Slow, MACD_Signal, PRICE_CLOSE, MODE_MAIN, 2);
   double macd_signal = iMACD(NULL, 0, MACD_Fast, MACD_Slow, MACD_Signal, PRICE_CLOSE, MODE_SIGNAL, 1);

   double bb_upper = iBands(NULL, 0, BB_Period, BB_Deviation, 0, BB_AppliedPrice, MODE_UPPER, 1);
   double bb_middle = iBands(NULL, 0, BB_Period, BB_Deviation, 0, BB_AppliedPrice, MODE_MAIN, 1);
   double bb_lower = iBands(NULL, 0, BB_Period, BB_Deviation, 0, BB_AppliedPrice, MODE_LOWER, 1);

   double rsi = iRSI(NULL, 0, RSI_Period, PRICE_CLOSE, 1);
   double rsi_prev = iRSI(NULL, 0, RSI_Period, PRICE_CLOSE, 2);

   double atr = iATR(NULL, 0, ATR_Period, 1);
   double atr_avg = 0;
   for(int i = 1; i <= 10; i++)
      atr_avg += iATR(NULL, 0, ATR_Period, i);
   atr_avg = atr_avg / 10;

   double ma = iMA(NULL, 0, MA_Period, 0, MA_Method, PRICE_CLOSE, 1);

   double close_1 = iClose(NULL, 0, 1);
   double close_2 = iClose(NULL, 0, 2);
   double high_1 = iHigh(NULL, 0, 1);
   double low_1 = iLow(NULL, 0, 1);

   // Filter: Check if volatility is not too high
   if(atr > atr_avg * ATR_MaxVolatility)
   {
      return 0; // Skip trading in high volatility
   }

   //--- BUY Signal Conditions (All must be true)
   bool buy_macd = (macd_main > macd_signal) && (macd_main_prev <= macd_signal); // MACD crossover up
   bool buy_bb = (close_2 <= bb_lower || low_1 <= bb_lower) && (close_1 > bb_lower); // Bounce from lower BB
   bool buy_rsi = (rsi_prev < RSI_Oversold) && (rsi >= RSI_Oversold); // RSI coming out of oversold
   bool buy_trend = close_1 > ma; // Price above MA (uptrend)
   bool buy_momentum = close_1 > close_2; // Bullish candle

   //--- SELL Signal Conditions (All must be true)
   bool sell_macd = (macd_main < macd_signal) && (macd_main_prev >= macd_signal); // MACD crossover down
   bool sell_bb = (close_2 >= bb_upper || high_1 >= bb_upper) && (close_1 < bb_upper); // Bounce from upper BB
   bool sell_rsi = (rsi_prev > RSI_Overbought) && (rsi <= RSI_Overbought); // RSI coming out of overbought
   bool sell_trend = close_1 < ma; // Price below MA (downtrend)
   bool sell_momentum = close_1 < close_2; // Bearish candle

   // Require strong confirmation from multiple indicators
   int buy_score = 0;
   if(buy_macd) buy_score++;
   if(buy_bb) buy_score++;
   if(buy_rsi) buy_score++;
   if(buy_trend) buy_score++;
   if(buy_momentum) buy_score++;

   int sell_score = 0;
   if(sell_macd) sell_score++;
   if(sell_bb) sell_score++;
   if(sell_rsi) sell_score++;
   if(sell_trend) sell_score++;
   if(sell_momentum) sell_score++;

   // Require at least 4 out of 5 confirmations for high precision
   if(buy_score >= 4)
   {
      Print("=== BUY SIGNAL DETECTED ===");
      Print("MACD: ", buy_macd, " | BB: ", buy_bb, " | RSI: ", buy_rsi, " | Trend: ", buy_trend, " | Momentum: ", buy_momentum);
      Print("Score: ", buy_score, "/5");
      return 1;
   }

   if(sell_score >= 4)
   {
      Print("=== SELL SIGNAL DETECTED ===");
      Print("MACD: ", sell_macd, " | BB: ", sell_bb, " | RSI: ", sell_rsi, " | Trend: ", sell_trend, " | Momentum: ", sell_momentum);
      Print("Score: ", sell_score, "/5");
      return -1;
   }

   return 0; // No clear signal
}

//+------------------------------------------------------------------+
//| Open Buy Position                                                  |
//+------------------------------------------------------------------+
void OpenBuyPosition()
{
   double atr = iATR(NULL, 0, ATR_Period, 1);
   double sl_distance = atr * ATR_SL_Multiplier;
   double tp_distance = sl_distance * RiskRewardRatio;

   double price = Ask;
   double sl = price - sl_distance;
   double tp = price + tp_distance;

   // Normalize prices
   sl = NormalizeDouble(sl, Digits);
   tp = NormalizeDouble(tp, Digits);

   // Calculate lot size based on risk
   double lots = CalculateLotSize(sl_distance);
   lots = NormalizeLots(lots);

   if(lots < MarketInfo(Symbol(), MODE_MINLOT))
   {
      Print("Lot size too small: ", lots);
      return;
   }

   int ticket = OrderSend(Symbol(), OP_BUY, lots, price, Slippage, sl, tp,
                         "Low DD EA Buy", MagicNumber, 0, clrGreen);

   if(ticket > 0)
   {
      LastTradeTime = TimeCurrent();
      LastTradeBar = Bars;
      Print("BUY Order Opened: Ticket=", ticket, " Lots=", lots, " SL=", sl, " TP=", tp);
      Print("Risk Amount: ", AccountBalance() * RiskPercent / 100, " Risk/Reward: 1:", RiskRewardRatio);
   }
   else
   {
      Print("BUY Order Failed! Error: ", GetLastError());
   }
}

//+------------------------------------------------------------------+
//| Open Sell Position                                                 |
//+------------------------------------------------------------------+
void OpenSellPosition()
{
   double atr = iATR(NULL, 0, ATR_Period, 1);
   double sl_distance = atr * ATR_SL_Multiplier;
   double tp_distance = sl_distance * RiskRewardRatio;

   double price = Bid;
   double sl = price + sl_distance;
   double tp = price - tp_distance;

   // Normalize prices
   sl = NormalizeDouble(sl, Digits);
   tp = NormalizeDouble(tp, Digits);

   // Calculate lot size based on risk
   double lots = CalculateLotSize(sl_distance);
   lots = NormalizeLots(lots);

   if(lots < MarketInfo(Symbol(), MODE_MINLOT))
   {
      Print("Lot size too small: ", lots);
      return;
   }

   int ticket = OrderSend(Symbol(), OP_SELL, lots, price, Slippage, sl, tp,
                         "Low DD EA Sell", MagicNumber, 0, clrRed);

   if(ticket > 0)
   {
      LastTradeTime = TimeCurrent();
      LastTradeBar = Bars;
      Print("SELL Order Opened: Ticket=", ticket, " Lots=", lots, " SL=", sl, " TP=", tp);
      Print("Risk Amount: ", AccountBalance() * RiskPercent / 100, " Risk/Reward: 1:", RiskRewardRatio);
   }
   else
   {
      Print("SELL Order Failed! Error: ", GetLastError());
   }
}

//+------------------------------------------------------------------+
//| Calculate Lot Size Based on Risk                                  |
//+------------------------------------------------------------------+
double CalculateLotSize(double sl_distance)
{
   double riskAmount = AccountBalance() * RiskPercent / 100;
   double tickValue = MarketInfo(Symbol(), MODE_TICKVALUE);
   double tickSize = MarketInfo(Symbol(), MODE_TICKSIZE);

   double lots = (riskAmount) / (sl_distance / tickSize * tickValue);

   return lots;
}

//+------------------------------------------------------------------+
//| Normalize Lot Size                                                |
//+------------------------------------------------------------------+
double NormalizeLots(double lots)
{
   double minLot = MarketInfo(Symbol(), MODE_MINLOT);
   double maxLot = MarketInfo(Symbol(), MODE_MAXLOT);
   double lotStep = MarketInfo(Symbol(), MODE_LOTSTEP);

   lots = MathFloor(lots / lotStep) * lotStep;

   if(lots < minLot) lots = minLot;
   if(lots > maxLot) lots = maxLot;

   return NormalizeDouble(lots, 2);
}

//+------------------------------------------------------------------+
//| Check if there is an open position                                |
//+------------------------------------------------------------------+
bool HasOpenPosition()
{
   for(int i = OrdersTotal() - 1; i >= 0; i--)
   {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
      {
         if(OrderSymbol() == Symbol() && OrderMagicNumber() == MagicNumber)
         {
            if(OrderType() == OP_BUY || OrderType() == OP_SELL)
               return true;
         }
      }
   }
   return false;
}

//+------------------------------------------------------------------+
//| Manage Trailing Stop                                              |
//+------------------------------------------------------------------+
void ManageTrailingStop()
{
   double trailStart = TrailingStart * Point * 10;
   double trailStop = TrailingStop * Point * 10;

   for(int i = OrdersTotal() - 1; i >= 0; i--)
   {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
      {
         if(OrderSymbol() == Symbol() && OrderMagicNumber() == MagicNumber)
         {
            if(OrderType() == OP_BUY)
            {
               double profit = Bid - OrderOpenPrice();
               if(profit >= trailStart)
               {
                  double newSL = Bid - trailStop;
                  newSL = NormalizeDouble(newSL, Digits);

                  if(newSL > OrderStopLoss())
                  {
                     bool result = OrderModify(OrderTicket(), OrderOpenPrice(), newSL, OrderTakeProfit(), 0, clrBlue);
                     if(result)
                        Print("Trailing Stop Updated for BUY: New SL=", newSL);
                  }
               }
            }
            else if(OrderType() == OP_SELL)
            {
               double profit = OrderOpenPrice() - Ask;
               if(profit >= trailStart)
               {
                  double newSL = Ask + trailStop;
                  newSL = NormalizeDouble(newSL, Digits);

                  if(newSL < OrderStopLoss() || OrderStopLoss() == 0)
                  {
                     bool result = OrderModify(OrderTicket(), OrderOpenPrice(), newSL, OrderTakeProfit(), 0, clrBlue);
                     if(result)
                        Print("Trailing Stop Updated for SELL: New SL=", newSL);
                  }
               }
            }
         }
      }
   }
}

//+------------------------------------------------------------------+
//| Check Drawdown Limit                                              |
//+------------------------------------------------------------------+
bool CheckDrawdownLimit()
{
   double currentBalance = AccountBalance();
   double currentEquity = AccountEquity();
   double drawdown = 0;

   if(currentBalance > 0)
   {
      drawdown = ((currentBalance - currentEquity) / currentBalance) * 100;
   }

   if(drawdown >= MaxDrawdown)
   {
      Print("WARNING: Maximum Drawdown Reached! Current DD: ", drawdown, "% | Max: ", MaxDrawdown, "%");
      return false;
   }

   return true;
}
//+------------------------------------------------------------------+
