English | [简体中文](/README-CN.md)
# ZFAKA One-Stop Online Sales Store
### Features
 - One-Stop Online Sales Store, the interface UI is very beautiful, convenient, green, secure and fast sales and purchase experience.
 * [ZFAKA](https://github.com/zlkbdotnet/zfaka)
# MugglePay
### Payment Features
 - Accept Cryptocurrencies: BTC, BTC (Lightning Network), ETH, USDT, BCH, LTC, EOS
 - Integration in 5 minutes
 - Transaction fee as low as 0%
## Sign Up  
 * [MugglePay.com](https://merchants.mugglepay.com/user/register?ref=MP38158552)
 
## GUID
 * Overwrite the file to the ZFAKA root directory 
 * If you have changed the login address of ZFAKA, please modify Admin in /application/modules/Admin/views/payment/tpl/  path to your own directory
 * Modify the database, run the following sql statement in the faka database
 *  ```sql
    INSERT INTO `t_payment` (`payment`, `payname`, `payimage`, `alias`, `sign_type`, `app_id`, `app_secret`, `ali_public_key`, `rsa_private_key`, `configure3`, `configure4`, `overtime`, `active`) VALUES
    ('Mugglepay', 'Mugglepay', '/res/images/pay/crypto.png', 'mugglepay', 'MD5', '', '', '', '', '', '0.00', 300, 0);
    ```
## How to Register
 1. First click on the Sign Up address above and use the invitation code MP38158552 to register
 2. Sign In[MugglePay Portal](https://merchants.mugglepay.com)
 3. Choose"Developer Center"->“API”->“Use on backend server”，click“Add Key”，Get ur Key。
<img src="https://github.com/huangfengye/MugglepayForZfaka/blob/master/%E8%8E%B7%E5%8F%96%E5%BA%94%E7%94%A8%E5%AF%86%E9%92%A5.png" />

 4. Put ur Key in Ur Zfaka payment setting
<img src="https://github.com/huangfengye/MugglepayForZfaka/blob/master/zfaka%E5%90%8E%E5%8F%B0%E8%AE%BE%E7%BD%AE.png" />

## Verification
 Please go to "Product Management"-->"Product Doc"，Apply for your permission<br />
 
 Please confirm that you have opened the required permissions.
 Note that if you only have "cryptocurrency", you can only accept cryptocurrency. If you need to open other payment methods, please follow the procedure on this page.

## FAQ
You can refer to digital currency for anonymous payment[FAQ](https://github.com/MugglePay/MugglePay/blob/master/README.md)。
If you have any qustion ,jion our Telegram [Technology Exchange Group](https://t.me/mugglepay)。