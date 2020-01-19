# Phone Bill Calculator Coding Kata

Given a phone calls string with the calls duration per number, like the one below, calculate the bill ammount in cents.

```
00:01:07,400-234-090
00:05:01,701-080-080
00:05:00,400-234-090
```
###Business rules:
     * The number with less minutes is not charged
     * Any fraction of minute will be consider as a whole minute
     * If a number has less than 5 minutes the amount is 3 cents per minute
     * If it is more or equal to 5 minutes then is 150 cents per minute

