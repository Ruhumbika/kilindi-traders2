# Mfumo wa Kukumbusha kwa SMS - Kilindi Traders

## Muhtasari
Mfumo huu unatuma SMS za kukumbusha kwa Kiswahili kwa wafanyabiashara kuhusu:
- Madeni yanayokaribia kuisha
- Madeni yaliyochelewa
- Leseni zinazokaribia kuisha
- Leseni zinazohitaji kufanywa upya

## Ratiba ya Kukumbusha

### 1. Kukumbusha Madeni Yanayokaribia (Siku 3 kabla)
**Wakati**: Kila siku saa 8:00 asubuhi
**Amri**: `php artisan sms:debt-due-reminders --days=3`
**Ujumbe**: 
```
Mpendwa [Jina], deni lako la TSh [Kiasi] kwa Halmashauri ya Wilaya ya Kilindi 
litakuwa na muda wa kulipwa tarehe [Tarehe]. Lipa mapema kwa kutumia namba ya 
udhibiti: [Namba]. Wasiliana nasi kwa msaada zaidi.
```

### 2. Kukumbusha Madeni Yaliyochelewa
**Wakati**: Kila siku saa 9:00 asubuhi
**Amri**: `php artisan sms:overdue-reminders`
**Ujumbe**:
```
MUHIMU: Mpendwa [Jina], deni lako la TSh [Kiasi] kwa Halmashauri ya Wilaya ya 
Kilindi lilikuwa na muda wa kulipwa tarehe [Tarehe]. Lipa haraka kwa kutumia 
namba ya udhibiti: [Namba]. Wasiliana nasi kutatua jambo hili.
```

### 3. Kukumbusha Kufanya Upya Leseni (Siku 30 kabla)
**Wakati**: Kila siku saa 10:00 asubuhi
**Amri**: `php artisan sms:license-renewal-reminders --days=30`
**Ujumbe**:
```
Mpendwa [Jina], leseni yako ya biashara '[Jina la Biashara]' itaisha tarehe [Tarehe]. 
Tafadhali ifanye upya mapema ili kuepuka vikwazo vya biashara. Lipa ada ya kufanya 
upya kwa kutumia namba ya udhibiti: [Namba]. Tembelea ofisi za Halmashauri ya 
Wilaya ya Kilindi kwa msaada zaidi.
```

### 4. Kukumbusha Leseni Zinazokaribia Kuisha (Siku 7 kabla)
**Wakati**: Kila Jumatatu saa 11:00 asubuhi
**Amri**: `php artisan sms:license-expiry-reminders`
**Ujumbe**:
```
Mpendwa [Jina], leseni yako ya biashara itaisha tarehe [Tarehe]. Tafadhali 
ifanye upya kwa Halmashauri ya Wilaya ya Kilindi. Lipa ada ya kufanya upya 
kwa kutumia namba ya udhibiti: [Namba].
```

## Jinsi ya Kuendesha Mfumo

### 1. Kuendesha Kwa Mikono (Kwa Majaribio)
```bash
# Kukumbusha madeni yanayokaribia (siku 3)
php artisan sms:debt-due-reminders --days=3

# Kukumbusha madeni yaliyochelewa
php artisan sms:overdue-reminders

# Kukumbusha kufanya upya leseni (siku 30)
php artisan sms:license-renewal-reminders --days=30

# Kukumbusha leseni zinazokaribia kuisha
php artisan sms:license-expiry-reminders
```

### 2. Kuendesha Kiotomatiki (Production)
Mfumo umeandaliwa kuendesha kiotomatiki kwa kutumia Laravel Task Scheduler:

```bash
# Ongeza hii kwenye crontab ya server
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Mipangilio ya Siku na Wakati

Unaweza kubadilisha siku za kukumbusha:

```bash
# Kukumbusha siku 5 kabla ya kuisha
php artisan sms:debt-due-reminders --days=5

# Kukumbusha wiki 2 kabla ya kuisha
php artisan sms:license-renewal-reminders --days=14
```

## Kufuatilia SMS Zilizotumwa

Angalia SMS zote zilizotumwa kupitia:
1. **Admin Panel**: Nenda kwenye "SMS Management"
2. **Database**: Angalia jedwali la `sms_logs`
3. **Laravel Logs**: Angalia `storage/logs/laravel.log`

## Faida za Mfumo Huu

✅ **Otomatiki kabisa** - Hakuna haja ya kuendesha kwa mikono
✅ **Lugha ya Kiswahili** - Ujumbe wote ni kwa Kiswahili
✅ **Namba za Udhibiti** - Kila ujumbe una namba ya malipo
✅ **Kufuatilia** - SMS zote zinarekodiwa kwenye mfumo
✅ **Kubadilika** - Unaweza kubadilisha siku za kukumbusha
✅ **Usalama** - Inatumia mfumo wa Laravel wa usalama

## Mahitaji

- Laravel Task Scheduler iwe imeandaliwa
- SMS API iwe imeunganishwa (Twilio, Africa's Talking, nk.)
- Database iwe na data za wafanyabiashara na madeni/leseni

## Msaada

Kama una matatizo:
1. Angalia Laravel logs: `tail -f storage/logs/laravel.log`
2. Jaribu kuendesha amri kwa mikono
3. Hakikisha SMS API iko sawa
4. Angalia database kwa data sahihi
