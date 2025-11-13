# Low Drawdown EA - MT4 Expert Advisor

## 📊 ภาพรวม

EA นี้ออกแบบมาเพื่อเทรดด้วยความแม่นยำสูง โดยเน้นการควบคุม Drawdown ให้อยู่ในระดับต่ำ ใช้หลาก Indicators มายืนยันสัญญาณก่อนเข้าเทรด ทำให้ออกไม้ไม่บ่อยแต่มีคุณภาพสูง

## 🎯 จุดเด่น

- **ความแม่นยำสูง**: ต้องมีการยืนยันจาก 4/5 indicators ก่อนเข้าเทรด
- **Drawdown ต่ำ**: มี Maximum Drawdown Limit ป้องกันการขาดทุนมากเกินไป
- **Risk Management**: คำนวณ Position Size อัตโนมัติตาม % ของ Account
- **ATR-Based Stops**: Stop Loss และ Take Profit คำนวณจาก ATR (เหมาะกับแต่ละตลาด)
- **Trailing Stop**: ล็อคกำไรอัตโนมัติเมื่อเข้าเป้า

## 🔧 Indicators ที่ใช้

### 1. MACD (Moving Average Convergence Divergence)
- ตรวจจับ momentum และ trend changes
- เข้า trade เมื่อมี crossover

### 2. Bollinger Bands
- หา overbought/oversold zones
- ตรวจจับ price reversal จากขอบ bands

### 3. RSI (Relative Strength Index)
- ยืนยัน oversold/overbought conditions
- ต้องเห็น reversal signal ก่อนเข้าเทรด

### 4. ATR (Average True Range)
- วัด market volatility
- กรองช่วงที่ volatility สูงเกินไป
- คำนวณ Stop Loss และ Take Profit

### 5. Moving Average (MA 200)
- กรอง trend direction
- BUY เฉพาะเมื่อราคาอยู่เหนือ MA
- SELL เฉพาะเมื่อราคาอยู่ใต้ MA

## 📋 เงื่อนไขการเข้าเทรด

### สัญญาณ BUY (ต้องผ่านอย่างน้อย 4/5 เงื่อนไข)

1. ✅ **MACD**: MACD line cross above Signal line
2. ✅ **Bollinger Bands**: ราคาแตะ Lower Band แล้วกลับขึ้น
3. ✅ **RSI**: RSI ต่ำกว่า 30 แล้วกลับขึ้นมา (oversold reversal)
4. ✅ **Trend**: ราคาอยู่เหนือ MA 200
5. ✅ **Momentum**: เป็น Bullish candle

### สัญญาณ SELL (ต้องผ่านอย่างน้อย 4/5 เงื่อนไข)

1. ✅ **MACD**: MACD line cross below Signal line
2. ✅ **Bollinger Bands**: ราคาแตะ Upper Band แล้วกลับลง
3. ✅ **RSI**: RSI สูงกว่า 70 แล้วกลับลงมา (overbought reversal)
4. ✅ **Trend**: ราคาอยู่ใต้ MA 200
5. ✅ **Momentum**: เป็น Bearish candle

## ⚙️ การตั้งค่าที่แนะนำ

### Risk Management (สำคัญมาก!)
```
RiskPercent = 1.0          // เสี่ยง 1% ต่อ trade
MaxDrawdown = 15.0         // หยุดเทรดถ้า DD เกิน 15%
RiskRewardRatio = 2.0      // Risk:Reward = 1:2
```

### MACD Settings
```
MACD_Fast = 12
MACD_Slow = 26
MACD_Signal = 9
```

### Bollinger Bands
```
BB_Period = 20
BB_Deviation = 2.0
```

### RSI Settings
```
RSI_Period = 14
RSI_Oversold = 30
RSI_Overbought = 70
```

### ATR Settings
```
ATR_Period = 14
ATR_SL_Multiplier = 2.0    // Stop Loss = 2 x ATR
ATR_MaxVolatility = 3.0    // ไม่เทรดถ้า ATR สูงกว่าค่าเฉลี่ย 3 เท่า
```

### Trading Settings
```
UseTrailingStop = true
TrailingStart = 20         // เริ่ม trail เมื่อกำไร 20 pips
TrailingStop = 15          // Trail ห่าง 15 pips
MinBarsBetweenTrades = 10  // ห่างระหว่าง trade อย่างน้อย 10 bars
```

## 📝 วิธีติดตั้ง

1. คัดลอกไฟล์ `LowDrawdownEA.mq4` ไปยัง folder:
   ```
   MT4/MQL4/Experts/
   ```

2. เปิด MT4 และ Compile EA:
   - เปิด MetaEditor (F4)
   - เปิดไฟล์ LowDrawdownEA.mq4
   - คลิก Compile (F7)
   - ตรวจสอบว่าไม่มี error

3. ลาก EA ไปยัง Chart:
   - เลือก timeframe ที่ต้องการ (แนะนำ H1 หรือ H4)
   - ลาก EA จาก Navigator ไปยัง Chart
   - ตั้งค่าตามที่ต้องการ
   - เปิด "Allow live trading" และ "Allow DLL imports"

## 🎮 การใช้งาน

### Timeframe ที่แนะนำ
- **H1 (1 Hour)**: สมดุลระหว่างความถี่และคุณภาพ signal
- **H4 (4 Hours)**: สัญญาณน้อยแต่แม่นยำมากขึ้น
- **D1 (Daily)**: เหมาะสำหรับ swing trading

### คู่เงินที่แนะนำ
- **Major Pairs**: EUR/USD, GBP/USD, USD/JPY (spread ต่ำ, liquidity สูง)
- **Gold**: XAU/USD (volatility พอดี)

### การตั้งค่าสำหรับผู้เริ่มต้น

**Conservative (ระมัดระวัง)**
```
RiskPercent = 0.5
MaxDrawdown = 10.0
RiskRewardRatio = 2.5
ATR_MaxVolatility = 2.5
MinBarsBetweenTrades = 15
```

**Standard (มาตรฐาน)**
```
RiskPercent = 1.0
MaxDrawdown = 15.0
RiskRewardRatio = 2.0
ATR_MaxVolatility = 3.0
MinBarsBetweenTrades = 10
```

**Aggressive (ก้าวร้าว)** - ไม่แนะนำถ้าไม่มีประสบการณ์
```
RiskPercent = 2.0
MaxDrawdown = 20.0
RiskRewardRatio = 1.5
ATR_MaxVolatility = 3.5
MinBarsBetweenTrades = 5
```

## 📊 วิธีอ่าน Log

EA จะพิมพ์ข้อมูลออกมาที่ Terminal เมื่อตรวจพบสัญญาณ:

```
=== BUY SIGNAL DETECTED ===
MACD: true | BB: true | RSI: true | Trend: true | Momentum: true
Score: 5/5
BUY Order Opened: Ticket=12345 Lots=0.10 SL=1.0850 TP=1.0950
Risk Amount: 100 Risk/Reward: 1:2
```

- **Score 5/5**: สัญญาณแม่นยำสุด (ผ่านทุกเงื่อนไข)
- **Score 4/5**: สัญญาณดี (ผ่าน 4 เงื่อนไข)

## ⚠️ คำเตือนและข้อควรระวัง

1. **Backtest ก่อนใช้จริง**: ทดสอบกับข้อมูลย้อนหลังอย่างน้อย 6-12 เดือน
2. **เริ่มด้วย Demo Account**: ทดลองกับ demo account ก่อนใช้เงินจริง
3. **ไม่มี EA ไหนชนะ 100%**: EA นี้ออกแบบมาเพื่อลด drawdown แต่ไม่ได้รับประกันกำไร
4. **ตรวจสอบ Spread**: EA นี้เหมาะกับ broker ที่มี spread ต่ำ
5. **News Events**: พิจารณาหยุดเทรดในช่วงข่าวเศรษฐกิจสำคัญ
6. **VPS แนะนำ**: ใช้ VPS เพื่อให้ EA ทำงาน 24/7 ไม่สะดุด

## 🔄 การปรับแต่งเพิ่มเติม

### ถ้าออกไม้บ่อยเกินไป
- เพิ่ม `MinBarsBetweenTrades`
- ลด `ATR_MaxVolatility`
- เพิ่มความเข้มงวดของ RSI levels (เช่น RSI_Oversold = 25)

### ถ้าออกไม้น้อยเกินไป
- ลด `MinBarsBetweenTrades`
- เพิ่ม `ATR_MaxVolatility`
- ผ่อนปรน RSI levels (เช่น RSI_Oversold = 35)

### ถ้า Drawdown สูงเกินไป
- ลด `RiskPercent` (ลงเหลือ 0.5% หรือน้อยกว่า)
- เพิ่ม `ATR_SL_Multiplier` (SL ไกลขึ้น)
- เพิ่ม `RiskRewardRatio` (รอกำไรมากขึ้น)

## 📈 Backtest แนะนำ

1. **Period**: อย่างน้อย 1 ปี
2. **Spread**: ใช้ spread จริงของ broker (หรือเพิ่ม 20%)
3. **Quality**: ใช้ tick data quality 99%
4. **Metrics ที่ควรดู**:
   - Maximum Drawdown < 20%
   - Profit Factor > 1.5
   - Win Rate > 50%
   - Risk/Reward Ratio ≈ ตามที่ตั้งไว้

## 💡 Tips สำหรับผลลัพธ์ที่ดี

1. **ใจเย็น**: EA นี้ออกไม้ไม่บ่อย อย่าเพิ่งปรับแต่งถ้ายังไม่เห็นผลอย่างน้อย 20-30 trades
2. **Diversification**: ใช้หลายคู่เงินเพื่อกระจายความเสี่ยง
3. **Monitor**: ตรวจสอบ EA เป็นประจำ โดยเฉพาะสัปดาห์แรก
4. **Journal**: บันทึกผลการเทรดและปรับปรุงการตั้งค่า

## 📞 การแก้ไขปัญหา

### EA ไม่เทรด
- ตรวจสอบว่าเปิด "Allow live trading" แล้ว
- ตรวจสอบว่า Drawdown ไม่เกิน MaxDrawdown
- ตรวจสอบว่ามี bars เพียงพอ (อย่างน้อย 100 bars)
- ดูที่ Terminal log ว่ามีข้อความอะไร

### Order Failed
- ตรวจสอบ lot size (อาจเล็กเกินไปหรือใหญ่เกินไป)
- ตรวจสอบว่ามี margin เพียงพอ
- ตรวจสอบ broker ว่าอนุญาตให้ EA เทรดหรือไม่

### Trailing Stop ไม่ทำงาน
- ตรวจสอบว่า `UseTrailingStop = true`
- ตรวจสอบว่ากำไรถึง `TrailingStart` แล้ว

## 📄 License

สามารถใช้ได้ฟรี แต่ใช้แล้วเสี่ยงเอง (Use at your own risk)

## 🤝 การสนับสนุน

หากมีคำถามหรือต้องการปรับแต่งเพิ่มเติม สามารถติดต่อได้

---

**⚡ Remember: Past performance does not guarantee future results. Always trade responsibly!**
