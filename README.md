# Skrypty pomocnicze dla rozszerzenia Chrome Sellasistant

### Instalacja
1. Wrzuć skrypty na serwer do jednego katalogu
2. Dostosuj zmienne w pliku config.php
3. W ustawieniach Sellasistanta podaj adresy skryptów

## Skrypty:

### mapper.php
Pozwola mapować produkty z Shopera na produkty w Sellasist. Może być używany niezależnie od Sellasistanta - poprostu skieruj przeglądarkę na adres skryptu.

![image](./images/mapper.png)

### postal.php
Odpytuje API, pobiera nazwę miejscowości po kodzie pocztowym i wstawia w pole na Miejscowość na stronie zamówienia

## pna.dp
Baza danych zawwierająca aktualne Miejscowości i kody (stan na styczeń 2025). Struktura bazy: tabela pna_2025, kolumny code oraz city

### mail_shortener.php
## ⚠️ UWAGA! Jeśli użyjesz shortenera nie będzie możliwe nadawanie przesyłek z nowym WzA - zalecane jest nie korzystanie z tej funkcji dopóki nie opublikowana zostanie poprawka skryptu lub Sellasistanta
Skraca adres Allegro z abc+xyz@allegromail.pl do abc@allegromail.pl aby umożliwić wyszukiwanie zamówień w Responso.
W Sellasist należy ustawić akcję automatyczną "WYWOŁAJ URL":

![image](./images/mail_shortener.png)

### sa_connector.php
Komunikujacja z API Sellasista
