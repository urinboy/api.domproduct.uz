# Local Email Development Setup

## MailHog (Local Email Testing)
# 1. MailHog ni o'rnating: https://github.com/mailhog/MailHog
# 2. MailHog ni ishga tushiring: mailhog
# 3. .env faylida:

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@domproduct.uz
MAIL_FROM_NAME="${APP_NAME}"

# 4. MailHog interface: http://localhost:8025

## Log Driver (Faqat log ga yozish)
MAIL_MAILER=log

## Array Driver (Xotiraga saqlash, test uchun)
MAIL_MAILER=array
