//+------------------------------------------------------------------+
//|                                    JumStoCh_Improved_v3_M5.mq4    |
//|              M5 Scalping Version - SAFE MODE (Profit Focused)     |
//|                 Based on Jum+StoCh v2.5 + Low DD Improvements     |
//+------------------------------------------------------------------+
#property copyright "Improved by Claude AI - M5 Safe Scalping Edition"
#property link      ""
#property version   "3.20"
#property strict

// ⭐ M5 SAFE MODE - PROFIT FOCUSED WITH RISK PROTECTION ⭐
// v3.20 IMPROVEMENTS:
// - Max Floating Loss Protection (Stop grid at 3% loss)
// - Auto Breakeven (Move SL when profit > 50% TP)
// - Faster Hedge Closing (Min 1 order, 0.3% target)
// - Stricter Signal Filters (RSI 35/65, Stoch 30/70)
// - Reduced DD Protection (8% Max DD, 5% Recovery Mode)
// - Safer Grid Settings (Range 25 pips, Level 6, TP 20 pips)

//--- Input Parameters
input string Section1 = "=== Risk Management & DD Protection ===";
input double MaxDrawdown = 8.0;               // ⭐ M5: Max DD % 8% (SAFER!)
input double DD_Recovery_Level = 5.0;         // ⭐ M5: DD % 5% to activate Recovery Mode
input double Max_Floating_Loss_Percent = 3.0; // ⭐ Max Floating Loss % (Stop Grid)
input bool   Risk_In_Money = FALSE;           // Use Fixed Risk Amount
input double Risk_in_money = 500.0;           // Max Loss Amount
input double Target_Persen = 3.0;             // Target Profit % (M5: Lower target, more trades)

input string Section2 = "=== Hedge Pair Closing System ===";
input bool   Enable_Hedge_Closing = TRUE;     // Enable Hedge Pair Closing
input int    Min_Orders_For_Hedge = 1;        // ⭐ M5: Min 1 Order (FASTER closing)
input double Hedge_Profit_Target = 0.3;       // ⭐ M5: 0.3% target (close quicker)
input bool   Hedge_In_Money = FALSE;          // Use Money Instead of %
input double Hedge_Profit_Money = 3.0;        // ⭐ M5: $3 target (lower)

input string Section3 = "=== Lot Management (Improved) ===";
input string Lot_info = "Mode 1=Compound; Mode 2=Fix Lot";
input int    Lot_mode = 2;                    // Lot Mode
input double Manage_Lot = 10000.0;            // Compound Divisor
input double Fix_lot = 0.01;                  // Fixed Lot Size
input int    Magic = 65;                      // ⭐ M5: Different Magic (65 for M5)

input string Section4 = "=== Grid Settings (M5 Optimized) ===";
input bool   Use_Dynamic_Range = TRUE;        // Use ATR for Dynamic Range
input double Range = 25.0;                    // ⭐ M5: 25 pips (SAFER spacing)
input double DiMarti = 1.25;                  // ⭐ M5: 1.25 (safer for frequent trades)
input int    Level_Max = 6;                   // ⭐ M5: 6 levels (REDUCED for safety)
input bool   Use_Stop_Loss = TRUE;            // ⭐ Enable/Disable Stop Loss
input double SL = 100.0;                      // ⭐ M5: 100 pips SL (tighter)
input bool   Use_Take_Profit = TRUE;          // ⭐ Enable/Disable Take Profit
input double TP = 20.0;                       // ⭐ M5: 20 pips TP (better profit)

input string Section5 = "=== Breakeven & Trailing ===";
input int    Star_ModifTp_Bep = 3;            // Start Modify TP from Level
input double Tp_from_Bep = 5.0;               // ⭐ M5: 5 pips from BEP
input bool   Dtrailing = TRUE;                // Enable Trailing Stop
input int    StartTrail = 8;                  // ⭐ M5: Start at 8 pips
input int    Trailing = 5;                    // ⭐ M5: Trail 5 pips

input string Section6 = "=== Trend Mode Settings ===";
input int    StartHour = 0;                   // Trend Start Hour
input int    StopHour = 24;                   // Trend Stop Hour
input bool   Buy_Trend = TRUE;                // Enable Buy Trend
input bool   Sell_Trend = TRUE;               // Enable Sell Trend

input string Section7 = "=== Counter Trend Settings ===";
input int    Start_Hour = 0;                  // Counter Start Hour
input int    Stop_Hour = 24;                  // Counter Stop Hour
input bool   Buy_Counter = TRUE;              // Enable Buy Counter
input bool   Sell_Counter = TRUE;             // Enable Sell Counter

input string Section8 = "=== Indicators (M5 Fast Response) ===";
input int    kperiod = 21;                    // ⭐ M5: Faster Stoch (21 vs 32)
input int    dperiod = 8;                     // ⭐ M5: Faster (8 vs 12)
input int    slowing = 8;                     // ⭐ M5: Faster (8 vs 12)
input int    lo_level = 30;                   // ⭐ M5: STRICTER (30 vs 35)
input int    up_level = 70;                   // ⭐ M5: STRICTER (70 vs 65)
input int    maPereode = 20;                  // ⭐ M5: Faster MA (20 vs 25)

input string Section9 = "=== Additional Filters (STRICTER for Safety) ===";
input int    RSI_Period = 14;                 // RSI Period
input double RSI_Oversold = 35;               // ⭐ M5: STRICTER (35 vs 40)
input double RSI_Overbought = 65;             // ⭐ M5: STRICTER (65 vs 60)
input int    ATR_Period = 14;                 // ATR Period
input double ATR_Volatility_Filter = 3.0;     // ⭐ M5: STRICTER (3.0 vs 4.0)

input string Section10 = "=== Manual Close Options ===";
input bool   Close_Panic = FALSE;             // Close All Orders
input bool   Close_Buy_Trend = FALSE;         // Close Buy Trend
input bool   Close_Sell_Trend = FALSE;        // Close Sell Trend
input bool   Close_Buy_Counter = FALSE;       // Close Buy Counter
input bool   Close_Sell_Counter = FALSE;      // Close Sell Counter

//--- Global Variables
double Gd_376;          // Tick Size Adjusted
double Gd_384;          // Stop Level
double G_lots_392;      // Current Lot Size
double Gd_400;          // Initial Balance
int G_ticket_408 = 0;   // Last Ticket
int G_magic_412;        // Buy Trend Magic
int G_magic_416;        // Sell Trend Magic
int G_magic_420;        // Buy Counter Magic
int G_magic_424;        // Sell Counter Magic

bool G_Recovery_Mode = false;    // DD Recovery Mode Flag
datetime G_Last_Hedge_Time = 0;  // Last Hedge Close Time

// Adjusted values for SL, TP, Trailing (validated in OnInit)
double G_SL;
double G_TP;
double G_Trailing;

string Gs_428 = "+Improved+BuyTrend+";
string Gs_436 = "+Improved+SellTrend+";
string Gs_444 = "+Improved+BuyCounter+";
string Gs_452 = "+Improved+SellCounter+";

//+------------------------------------------------------------------+
//| Expert initialization function                                     |
//+------------------------------------------------------------------+
int OnInit()
{
   Gd_400 = AccountBalance();
   Gd_376 = MarketInfo(Symbol(), MODE_TICKSIZE);
   int Li_0 = 1;

   if(Digits == 3 || Digits == 5) {
      Gd_376 = 10.0 * Gd_376;
      Li_0 = 10 * Li_0;
   }

   Gd_384 = MarketInfo(Symbol(), MODE_STOPLEVEL) / Li_0;

   // Validate Settings and store in global variables
   G_SL = SL;
   G_TP = TP;
   G_Trailing = Trailing;

   if(G_SL > 0.0 && G_SL < Gd_384) {
      Print("StopLoss too tight, adjusting from ", G_SL, " to minimum: ", Gd_384);
      G_SL = Gd_384;
   }
   if(G_TP > 0.0 && G_TP < Gd_384) {
      Print("TakeProfit too tight, adjusting from ", G_TP, " to minimum: ", Gd_384);
      G_TP = Gd_384;
   }
   if(G_Trailing < Gd_384) {
      Print("Trailing too tight, adjusting from ", G_Trailing, " to minimum: ", Gd_384);
      G_Trailing = Gd_384;
   }

   // Set Magic Numbers
   G_magic_412 = Magic + 9;
   G_magic_416 = Magic + 99;
   G_magic_420 = Magic + 999;
   G_magic_424 = Magic + 9999;

   if(Lot_mode == 2) G_lots_392 = f0_7(Fix_lot);

   Print("===== Jum+StoCh M5 Scalping v3.20 SAFE MODE =====");
   Print("⭐ PROFIT FOCUSED - IMPROVED RISK MANAGEMENT ⭐");
   Print("Initial Balance: $", Gd_400);
   Print("--- SAFETY FEATURES ---");
   Print("Max DD Protection: ", MaxDrawdown, "% (REDUCED)");
   Print("DD Recovery Mode: ", DD_Recovery_Level, "%");
   Print("Max Floating Loss: ", Max_Floating_Loss_Percent, "% (NEW!)");
   Print("Auto Breakeven: ENABLED (at 50% TP profit)");
   Print("--- GRID SETTINGS ---");
   Print("Range: ", Range, " pips | Levels: ", Level_Max, " | Martingale: ", DiMarti);
   Print("Stop Loss: ", Use_Stop_Loss ? "ON (" + DoubleToString(G_SL, 1) + " pips)" : "OFF");
   Print("Take Profit: ", Use_Take_Profit ? "ON (" + DoubleToString(G_TP, 1) + " pips)" : "OFF");
   Print("--- HEDGE CLOSING ---");
   Print("Min Orders: ", Min_Orders_For_Hedge, " | Target: ", Hedge_Profit_Target, "% (FASTER)");
   Print("--- SIGNAL FILTERS (STRICTER) ---");
   Print("RSI: ", RSI_Oversold, "/", RSI_Overbought, " | Stoch: ", lo_level, "/", up_level);
   Print("ATR Filter: ", ATR_Volatility_Filter, "x");
   Print("==================================================");

   return(INIT_SUCCEEDED);
}

//+------------------------------------------------------------------+
//| Expert deinitialization function                                   |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
{
   ObjectsDeleteAll(0, OBJ_LABEL);
   Print("EA Stopped. Final Balance: ", AccountBalance());
}

//+------------------------------------------------------------------+
//| Expert tick function                                               |
//+------------------------------------------------------------------+
void OnTick()
{
   if(Bars < 100) return;

   // Update Lot Size
   if(Lot_mode == 1) G_lots_392 = f0_7(AccountBalance() / Manage_Lot);
   if(Lot_mode < 1 || Lot_mode > 2) {
      Comment("Invalid Lot_Mode");
      return;
   }

   // Update Display
   f0_2();

   // Check Current DD
   double current_dd = f0_GetCurrentDD();

   // Update Recovery Mode Status
   if(current_dd >= DD_Recovery_Level) {
      if(!G_Recovery_Mode) {
         G_Recovery_Mode = true;
         Print("*** DD RECOVERY MODE ACTIVATED! Current DD: ", current_dd, "% ***");
      }
   } else if(current_dd < DD_Recovery_Level * 0.5) {
      if(G_Recovery_Mode) {
         G_Recovery_Mode = false;
         Print("*** DD Recovery Mode Deactivated. DD: ", current_dd, "% ***");
      }
   }

   // === HEDGE PAIR CLOSING SYSTEM ===
   if(Enable_Hedge_Closing) {
      f0_HedgePairClosing();
   }

   // === TARGET & RISK CHECK ===
   double Ld_0 = Gd_400 * Target_Persen / 100.0;
   if(AccountEquity() >= Gd_400 + Ld_0 ||
      (Risk_In_Money && AccountEquity() <= Gd_400 - Risk_in_money) ||
      Close_Panic) {
      Print("=== CLOSING ALL ORDERS: Target/Risk Reached ===");
      f0_3(G_magic_412);
      f0_3(G_magic_416);
      f0_3(G_magic_420);
      f0_3(G_magic_424);
      return;
   }

   // Manual Close Options
   if(Close_Buy_Trend) f0_3(G_magic_412);
   if(Close_Sell_Trend) f0_3(G_magic_416);
   if(Close_Buy_Counter) f0_3(G_magic_420);
   if(Close_Sell_Counter) f0_3(G_magic_424);

   // === MAX DD PROTECTION ===
   if(current_dd >= MaxDrawdown) {
      Comment("*** MAX DRAWDOWN REACHED! No New Trades. Current DD: ", current_dd, "% ***");
      // Don't open new trades, but continue managing existing ones
   }

   // === GRID MANAGEMENT ===
   if(!Close_Panic) {
      f0_8(G_magic_412, Gs_428);  // Add grid orders
      f0_8(G_magic_416, Gs_436);
      f0_8(G_magic_420, Gs_444);
      f0_8(G_magic_424, Gs_452);
   }

   // === TP & BREAKEVEN MANAGEMENT ===
   f0_9(G_magic_412);
   f0_9(G_magic_416);
   f0_9(G_magic_420);
   f0_9(G_magic_424);

   // === AUTO BREAKEVEN (Protect Profits) ===
   f0_AutoBreakeven(G_magic_412);
   f0_AutoBreakeven(G_magic_416);
   f0_AutoBreakeven(G_magic_420);
   f0_AutoBreakeven(G_magic_424);

   // === TRAILING STOP ===
   if(Dtrailing) {
      f0_14(G_magic_412);
      f0_14(G_magic_416);
      f0_14(G_magic_420);
      f0_14(G_magic_424);
   }

   // === NEW TRADE ENTRY ===
   // Don't open new trades if DD is too high or in panic mode
   if(!Close_Panic && current_dd < MaxDrawdown) {
      // Calculate SL and TP (or 0 if disabled)
      double buy_sl = Use_Stop_Loss ? Ask - G_SL * Gd_376 : 0;
      double buy_tp = Use_Take_Profit ? Ask + G_TP * Gd_376 : 0;
      double sell_sl = Use_Stop_Loss ? Bid + G_SL * Gd_376 : 0;
      double sell_tp = Use_Take_Profit ? Bid - G_TP * Gd_376 : 0;

      // Open first orders only if no positions exist
      if((!Close_Buy_Trend) && Buy_Trend && f0_13(G_magic_412) == 0 && f0_1(1) == -2)
         G_ticket_408 = OrderSend(Symbol(), OP_BUY, G_lots_392, Ask, 3, buy_sl, buy_tp, Gs_428 + "0", G_magic_412, 0, clrLime);

      if((!Close_Sell_Trend) && Sell_Trend && f0_13(G_magic_416) == 0 && f0_1(1) == 2)
         G_ticket_408 = OrderSend(Symbol(), OP_SELL, G_lots_392, Bid, 3, sell_sl, sell_tp, Gs_436 + "0", G_magic_416, 0, clrOrange);

      if((!Close_Buy_Counter) && Buy_Counter && f0_13(G_magic_420) == 0 && f0_1(-1) == -2)
         G_ticket_408 = OrderSend(Symbol(), OP_BUY, G_lots_392, Ask, 3, buy_sl, buy_tp, Gs_444 + "0", G_magic_420, 0, clrBlue);

      if((!Close_Sell_Counter) && Sell_Counter && f0_13(G_magic_424) == 0 && f0_1(-1) == 2)
         G_ticket_408 = OrderSend(Symbol(), OP_SELL, G_lots_392, Bid, 3, sell_sl, sell_tp, Gs_452 + "0", G_magic_424, 0, clrRed);
   }
}

//+------------------------------------------------------------------+
//| Get Current Drawdown Percentage                                   |
//+------------------------------------------------------------------+
double f0_GetCurrentDD()
{
   double balance = AccountBalance();
   double equity = AccountEquity();
   if(balance <= 0) return 0;

   double dd = ((balance - equity) / balance) * 100.0;
   return MathMax(0, dd);
}

//+------------------------------------------------------------------+
//| Get Current Floating Loss Percentage                              |
//+------------------------------------------------------------------+
double f0_GetFloatingLoss()
{
   double total_profit = 0;
   for(int i = 0; i < OrdersTotal(); i++) {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && OrderType() <= OP_SELL) {
            total_profit += OrderProfit() + OrderSwap() + OrderCommission();
         }
      }
   }

   double balance = AccountBalance();
   if(balance <= 0) return 0;

   // If losing (negative profit), calculate as % of balance
   if(total_profit < 0) {
      return (MathAbs(total_profit) / balance) * 100.0;
   }

   return 0;  // No floating loss
}

//+------------------------------------------------------------------+
//| Auto Breakeven Management - Move SL to BE when in profit          |
//+------------------------------------------------------------------+
void f0_AutoBreakeven(int A_magic_0)
{
   // Only apply if SL is enabled
   if(!Use_Stop_Loss) return;

   double breakeven_buffer = 5.0;  // 5 pips buffer above/below BE

   for(int pos = OrdersTotal() - 1; pos >= 0; pos--) {
      if(OrderSelect(pos, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && OrderMagicNumber() == A_magic_0) {
            if(OrderType() == OP_BUY) {
               // Move SL to BE+buffer when profit > TP/2
               double current_profit = (Bid - OrderOpenPrice()) / Gd_376;
               if(current_profit >= G_TP * 0.5) {  // Profit > 50% of TP
                  double new_sl = OrderOpenPrice() + breakeven_buffer * Gd_376;
                  // Only move SL up, never down
                  if(OrderStopLoss() == 0 || new_sl > OrderStopLoss()) {
                     if(new_sl < Bid - Gd_384 * Gd_376) {  // Respect stop level
                        OrderModify(OrderTicket(), OrderOpenPrice(), new_sl, OrderTakeProfit(), 0, clrBlue);
                     }
                  }
               }
            }
            else if(OrderType() == OP_SELL) {
               // Move SL to BE-buffer when profit > TP/2
               double current_profit = (OrderOpenPrice() - Ask) / Gd_376;
               if(current_profit >= G_TP * 0.5) {  // Profit > 50% of TP
                  double new_sl = OrderOpenPrice() - breakeven_buffer * Gd_376;
                  // Only move SL down, never up
                  if(OrderStopLoss() == 0 || new_sl < OrderStopLoss()) {
                     if(new_sl > Ask + Gd_384 * Gd_376) {  // Respect stop level
                        OrderModify(OrderTicket(), OrderOpenPrice(), new_sl, OrderTakeProfit(), 0, clrRed);
                     }
                  }
               }
            }
         }
      }
   }
}

//+------------------------------------------------------------------+
//| Hedge Pair Closing System (Gold Stuff Style)                      |
//+------------------------------------------------------------------+
void f0_HedgePairClosing()
{
   // Don't close too frequently
   if(TimeCurrent() - G_Last_Hedge_Time < 30) return;

   // Check each magic group
   f0_HedgeCloseMagicGroup(G_magic_412, G_magic_416);  // Trend Buy vs Trend Sell
   f0_HedgeCloseMagicGroup(G_magic_420, G_magic_424);  // Counter Buy vs Counter Sell
}

//+------------------------------------------------------------------+
//| Hedge Close for Specific Magic Group                              |
//+------------------------------------------------------------------+
void f0_HedgeCloseMagicGroup(int magic_buy, int magic_sell)
{
   int buy_count = f0_13(magic_buy);
   int sell_count = f0_13(magic_sell);
   int total_orders = buy_count + sell_count;

   // Need minimum orders
   if(total_orders < Min_Orders_For_Hedge) return;

   // Calculate combined profit
   double total_profit = f0_6(magic_buy) + f0_6(magic_sell);

   // Calculate target profit
   double target_profit;
   if(Hedge_In_Money) {
      target_profit = Hedge_Profit_Money;
   } else {
      target_profit = AccountBalance() * Hedge_Profit_Target / 100.0;
   }

   // In Recovery Mode, accept smaller profit
   if(G_Recovery_Mode) {
      target_profit = target_profit * 0.5;  // Accept 50% of normal target
   }

   // Close pairs if profitable
   if(total_profit >= target_profit) {
      Print("=== HEDGE PAIR CLOSING ===");
      Print("Magic Buy: ", magic_buy, " (", buy_count, " orders) | Magic Sell: ", magic_sell, " (", sell_count, " orders)");
      Print("Combined Profit: $", total_profit, " | Target: $", target_profit);

      f0_3(magic_buy);
      f0_3(magic_sell);

      G_Last_Hedge_Time = TimeCurrent();
      Print("=== Hedge Pairs Closed Successfully ===");
   }
}

//+------------------------------------------------------------------+
//| Signal Detection with Improved Confirmation                       |
//+------------------------------------------------------------------+
int f0_1(int Ai_0)
{
   // Original indicators
   double ima_4 = iMA(Symbol(), 0, maPereode, 0, MODE_LWMA, PRICE_CLOSE, 0);
   double istochastic_12 = iStochastic(NULL, 0, kperiod, dperiod, slowing, MODE_SMA, 0, MODE_MAIN, 0);
   double istochastic_prev = iStochastic(NULL, 0, kperiod, dperiod, slowing, MODE_SMA, 0, MODE_MAIN, 1);

   // Additional filters
   double rsi = iRSI(NULL, 0, RSI_Period, PRICE_CLOSE, 0);
   double atr = iATR(NULL, 0, ATR_Period, 1);
   double atr_avg = 0;
   for(int i = 1; i <= 10; i++)
      atr_avg += iATR(NULL, 0, ATR_Period, i);
   atr_avg = atr_avg / 10.0;

   // Volatility Filter
   if(atr > atr_avg * ATR_Volatility_Filter) {
      return 0;  // Too volatile
   }

   // === TREND MODE ===
   if(Ai_0 == 1 && f0_0() == 1) {
      // SELL Signal (STRICTER)
      if(Close[1] < ima_4 && istochastic_12 > lo_level && rsi > 50) {
         if(istochastic_12 > istochastic_prev) {  // Stoch rising
            // Additional confirmation: Price should be below MA
            if(Close[0] < ima_4) {
               return 2;
            }
         }
      }
      // BUY Signal (STRICTER)
      if(Close[1] > ima_4 && istochastic_12 < up_level && rsi < 50) {
         if(istochastic_12 < istochastic_prev) {  // Stoch falling
            // Additional confirmation: Price should be above MA
            if(Close[0] > ima_4) {
               return -2;
            }
         }
      }
   }

   // === COUNTER TREND MODE ===
   if(Ai_0 == -1 && f0_4() == 1) {
      // SELL Signal - Overbought with STRICT confirmation
      if(istochastic_12 > up_level && rsi > RSI_Overbought) {
         // Additional: Check if Stoch is turning down
         if(istochastic_12 < istochastic_prev) {
            return 2;
         }
      }
      // BUY Signal - Oversold with STRICT confirmation
      if(istochastic_12 < lo_level && rsi < RSI_Oversold) {
         // Additional: Check if Stoch is turning up
         if(istochastic_12 > istochastic_prev) {
            return -2;
         }
      }
   }

   return 0;
}

//+------------------------------------------------------------------+
//| Calculate Profit for Magic Number                                 |
//+------------------------------------------------------------------+
double f0_6(int A_magic_0)
{
   double Ld_ret_4 = 0;
   for(int pos_12 = 0; pos_12 < OrdersTotal(); pos_12++) {
      if(OrderSelect(pos_12, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && OrderType() <= OP_SELL) {
            if(A_magic_0 == OrderMagicNumber())
               Ld_ret_4 += OrderProfit() + OrderSwap() + OrderCommission();
         }
      }
   }
   return Ld_ret_4;
}

//+------------------------------------------------------------------+
//| Close All Orders for Magic Number                                 |
//+------------------------------------------------------------------+
void f0_3(int A_magic_0)
{
   for(int pos_4 = OrdersTotal() - 1; pos_4 >= 0; pos_4--) {
      if(OrderSelect(pos_4, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && A_magic_0 == OrderMagicNumber()) {
            if(OrderType() > OP_SELL) {
               OrderDelete(OrderTicket());
            } else if(OrderType() == OP_BUY) {
               OrderClose(OrderTicket(), OrderLots(), Bid, 3, CLR_NONE);
            } else if(OrderType() == OP_SELL) {
               OrderClose(OrderTicket(), OrderLots(), Ask, 3, CLR_NONE);
            }
         }
      }
   }
}

//+------------------------------------------------------------------+
//| Grid Order Management (Martingale with DD Protection)             |
//+------------------------------------------------------------------+
void f0_8(int A_magic_0, string As_4)
{
   int Li_12 = f0_13(A_magic_0);

   // Don't add more orders if at max level or DD too high
   if(Li_12 >= Level_Max) return;
   if(f0_GetCurrentDD() >= MaxDrawdown * 0.8) return;  // Stop at 80% of max DD

   // ⭐ NEW: Stop opening grid if floating loss exceeds limit
   double current_floating_loss = f0_GetFloatingLoss();
   if(current_floating_loss >= Max_Floating_Loss_Percent) {
      if(Li_12 > 0) {  // Only print if we have existing orders
         static datetime last_warning = 0;
         if(TimeCurrent() - last_warning > 300) {  // Print once every 5 minutes
            Print("⚠️ Floating Loss ", DoubleToString(current_floating_loss, 2), "% - Grid stopped for Magic ", A_magic_0);
            last_warning = TimeCurrent();
         }
      }
      return;
   }

   if(Li_12 > 0 && Li_12 < Level_Max) {
      int cmd_16;
      double order_open_price_20 = 0;
      double order_lots_28 = 0;

      // Find last order
      for(int pos_36 = OrdersTotal() - 1; pos_36 >= 0; pos_36--) {
         if(OrderSelect(pos_36, SELECT_BY_POS, MODE_TRADES)) {
            if(OrderSymbol() == Symbol() && OrderMagicNumber() == A_magic_0) {
               cmd_16 = OrderType();
               order_open_price_20 = OrderOpenPrice();
               order_lots_28 = OrderLots();
               break;
            }
         }
      }

      if(order_lots_28 == 0) return;

      // Dynamic Range based on ATR
      double grid_range = Range;
      if(Use_Dynamic_Range) {
         double atr = iATR(NULL, 0, ATR_Period, 1);
         grid_range = MathMax(Range, atr / Gd_376 * 10);  // Min = Range setting
      }

      double Ld_40 = order_open_price_20 - grid_range * Gd_376;
      if(cmd_16 == OP_BUY && Ask <= Ld_40) {
         double new_lot = f0_7(order_lots_28 * DiMarti);
         double grid_buy_sl = Use_Stop_Loss ? Ask - G_SL * Gd_376 : 0;
         double grid_buy_tp = Use_Take_Profit ? Ask + G_TP * Gd_376 : 0;
         G_ticket_408 = OrderSend(Symbol(), OP_BUY, new_lot, Ask, 3, grid_buy_sl, grid_buy_tp,
                                  As_4 + IntegerToString(Li_12), A_magic_0, 0, clrGreen);
         if(G_ticket_408 > 0)
            Print("Grid BUY added: Level=", Li_12, " Lot=", new_lot, " SL=", Use_Stop_Loss?"ON":"OFF", " TP=", Use_Take_Profit?"ON":"OFF");
      }

      Ld_40 = order_open_price_20 + grid_range * Gd_376;
      if(cmd_16 == OP_SELL && Bid >= Ld_40) {
         double new_lot = f0_7(order_lots_28 * DiMarti);
         double grid_sell_sl = Use_Stop_Loss ? Bid + G_SL * Gd_376 : 0;
         double grid_sell_tp = Use_Take_Profit ? Bid - G_TP * Gd_376 : 0;
         G_ticket_408 = OrderSend(Symbol(), OP_SELL, new_lot, Bid, 3, grid_sell_sl, grid_sell_tp,
                                  As_4 + IntegerToString(Li_12), A_magic_0, 0, clrYellow);
         if(G_ticket_408 > 0)
            Print("Grid SELL added: Level=", Li_12, " Lot=", new_lot, " SL=", Use_Stop_Loss?"ON":"OFF", " TP=", Use_Take_Profit?"ON":"OFF");
      }
   }
}

//+------------------------------------------------------------------+
//| Modify TP to Breakeven                                            |
//+------------------------------------------------------------------+
void f0_9(int A_magic_0)
{
   int Li_4 = f0_13(A_magic_0);
   if(Li_4 == 0) return;
   if(Tp_from_Bep == 0.0 || G_TP == 0.0) return;

   double Ld_8 = MathMax(f0_12(A_magic_0, OP_SELL), f0_12(A_magic_0, OP_BUY));

   for(int pos_24 = OrdersTotal() - 1; pos_24 >= 0; pos_24--) {
      if(OrderSelect(pos_24, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && OrderMagicNumber() == A_magic_0) {
            double price_16;
            if(Li_4 < Star_ModifTp_Bep)
               price_16 = f0_11(OrderType(), G_TP, OrderOpenPrice());
            else
               price_16 = f0_11(OrderType(), Tp_from_Bep, Ld_8);

            if(!f0_10(price_16, OrderTakeProfit()))
               OrderModify(OrderTicket(), OrderOpenPrice(), OrderStopLoss(), price_16, 0, CLR_NONE);
         }
      }
   }
}

//+------------------------------------------------------------------+
//| Count Orders for Magic Number                                     |
//+------------------------------------------------------------------+
int f0_13(int A_magic_0)
{
   int count_4 = 0;
   for(int pos_8 = 0; pos_8 < OrdersTotal(); pos_8++) {
      if(OrderSelect(pos_8, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && A_magic_0 == OrderMagicNumber())
            count_4++;
      }
   }
   return count_4;
}

//+------------------------------------------------------------------+
//| Check Trading Hours - Trend                                       |
//+------------------------------------------------------------------+
int f0_0()
{
   int current_hour = TimeHour(TimeCurrent());
   if(StartHour > StopHour) {
      if(current_hour >= StartHour || current_hour < StopHour) return 1;
   } else {
      if(current_hour >= StartHour && current_hour < StopHour) return 1;
   }
   return 0;
}

//+------------------------------------------------------------------+
//| Check Trading Hours - Counter                                     |
//+------------------------------------------------------------------+
int f0_4()
{
   int current_hour = TimeHour(TimeCurrent());
   if(Start_Hour > Stop_Hour) {
      if(current_hour >= Start_Hour || current_hour < Stop_Hour) return 1;
   } else {
      if(current_hour >= Start_Hour && current_hour < Stop_Hour) return 1;
   }
   return 0;
}

//+------------------------------------------------------------------+
//| Display Information                                                |
//+------------------------------------------------------------------+
void f0_2()
{
   double current_dd = f0_GetCurrentDD();
   double floating_loss = f0_GetFloatingLoss();
   string recovery_status = G_Recovery_Mode ? "*** RECOVERY MODE ***" : "Normal";

   Comment(
      "\n ==========================================",
      "\n  ⭐ Jum+StoCh M5 v3.20 SAFE MODE ⭐",
      "\n ==========================================",
      "\n  Balance: $", DoubleToString(AccountBalance(), 2),
      "\n  Equity: $", DoubleToString(AccountEquity(), 2),
      "\n  Floating P/L: $", DoubleToString(AccountEquity() - AccountBalance(), 2),
      "\n ------------------------------------------",
      "\n  Current DD: ", DoubleToString(current_dd, 2), "% / ", MaxDrawdown, "%",
      "\n  Floating Loss: ", DoubleToString(floating_loss, 2), "% / ", Max_Floating_Loss_Percent, "%",
      "\n  Status: ", recovery_status,
      "\n ------------------------------------------",
      "\n  Buy Trend: ", f0_13(G_magic_412), " | Sell Trend: ", f0_13(G_magic_416),
      "\n  Buy Counter: ", f0_13(G_magic_420), " | Sell Counter: ", f0_13(G_magic_424),
      "\n ------------------------------------------",
      "\n  Profit Trend: $", DoubleToString(f0_6(G_magic_412) + f0_6(G_magic_416), 2),
      "\n  Profit Counter: $", DoubleToString(f0_6(G_magic_420) + f0_6(G_magic_424), 2),
      "\n ------------------------------------------",
      "\n  Lot: ", DoubleToString(G_lots_392, 2), " | Range: ", Range,
      "\n  Hedge: ", Enable_Hedge_Closing ? "ON" : "OFF", " | Auto BE: ", Use_Stop_Loss ? "ON" : "OFF",
      "\n =========================================="
   );
}

//+------------------------------------------------------------------+
//| Trailing Stop Management                                          |
//+------------------------------------------------------------------+
void f0_14(int A_magic_0)
{
   double Ld_8 = f0_12(A_magic_0, OP_BUY);
   double Ld_16 = f0_12(A_magic_0, OP_SELL);

   for(int pos_32 = OrdersTotal() - 1; pos_32 >= 0; pos_32--) {
      if(OrderSelect(pos_32, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && OrderMagicNumber() == A_magic_0) {
            if(OrderType() == OP_BUY) {
               int Li_4 = NormalizeDouble((Bid - Ld_8) / Gd_376, 0);
               if(Li_4 >= StartTrail) {
                  double price_24 = NormalizeDouble(Bid - G_Trailing * Gd_376, Digits);
                  if(OrderStopLoss() == 0.0 || price_24 > OrderStopLoss())
                     OrderModify(OrderTicket(), OrderOpenPrice(), price_24, OrderTakeProfit(), 0, clrAqua);
               }
            }
            else if(OrderType() == OP_SELL) {
               int Li_4 = NormalizeDouble((Ld_16 - Ask) / Gd_376, 0);
               if(Li_4 >= StartTrail) {
                  double price_24 = NormalizeDouble(Ask + G_Trailing * Gd_376, Digits);
                  if(OrderStopLoss() == 0.0 || price_24 < OrderStopLoss())
                     OrderModify(OrderTicket(), OrderOpenPrice(), price_24, OrderTakeProfit(), 0, clrPink);
               }
            }
         }
      }
   }
}

//+------------------------------------------------------------------+
//| Calculate Average Entry Price                                     |
//+------------------------------------------------------------------+
double f0_12(int A_magic_0, int A_cmd_4)
{
   double Ld_ret_8 = 0;
   double Ld_16 = 0;

   for(int pos_24 = OrdersTotal() - 1; pos_24 >= 0; pos_24--) {
      if(OrderSelect(pos_24, SELECT_BY_POS, MODE_TRADES)) {
         if(OrderSymbol() == Symbol() && A_cmd_4 == OrderType() && A_magic_0 == OrderMagicNumber()) {
            Ld_ret_8 += OrderOpenPrice() * OrderLots();
            Ld_16 += OrderLots();
         }
      }
   }

   if(Ld_16 > 0.0)
      Ld_ret_8 = NormalizeDouble(Ld_ret_8 / Ld_16, Digits);

   return Ld_ret_8;
}

//+------------------------------------------------------------------+
//| Calculate TP Price                                                |
//+------------------------------------------------------------------+
double f0_11(int Ai_0, double Ai_4, double Ad_8)
{
   if(Ai_4 == 0) return 0;
   if(MathMod(Ai_0, 2) == 0.0)
      return Ad_8 + Gd_376 * Ai_4;
   return Ad_8 - Gd_376 * Ai_4;
}

//+------------------------------------------------------------------+
//| Normalize Lot Size                                                |
//+------------------------------------------------------------------+
double f0_7(double Ad_0)
{
   double maxlot_8 = MarketInfo(Symbol(), MODE_MAXLOT);
   double minlot_16 = MarketInfo(Symbol(), MODE_MINLOT);
   double lotstep_24 = MarketInfo(Symbol(), MODE_LOTSTEP);

   double Ld_32 = lotstep_24 * NormalizeDouble(Ad_0 / lotstep_24, 0);
   Ld_32 = MathMax(MathMin(maxlot_8, Ld_32), minlot_16);

   return Ld_32;
}

//+------------------------------------------------------------------+
//| Compare Two Prices                                                |
//+------------------------------------------------------------------+
bool f0_10(double Ad_0, double Ad_8)
{
   return NormalizeDouble(Ad_0 / Point, 0) == NormalizeDouble(Ad_8 / Point, 0);
}
//+------------------------------------------------------------------+
