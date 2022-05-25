# ZadanieRekturatacyjneM-odszyProgramista
Zadanie rekrutacyjne na stanowisko młodszy programista.

|Nazwa akcji|Metoda HTTP|URL|Dodatkowe parametry|
|------------|----------|---|----------------|
|getBrandsList|GET|/api/v1/car/brand||
|createBrand|POST|/api/v1/car/brand|string brand_name||
|getBrandById|GET|/api/v1/car/brand/{id}||
|deleteBrand|DELETE|/api/v1/car/brand/{id}||
|dettachCarFromBrand|PATCH|/api/v1/car/brand/dettachCar/{brand}|int car_model|
|attachCarToBrand|PATCH|/api/v1/car/brand/attachCar/{brand}|
|updateBrand|PATCH|/api/v1/car/brand/{id}|string brand_name|



|Nazwa akcji|Metoda HTTP|URL|Dodatkowe parametry|
|------------|----------|---|----------------|
|getCarsList|GET|/api/v1/car/model||
|createCar|POST|/api/v1/car/model|id brand_id||
|getCarById|GET|/api/v1/car/model/{id}||
|deleteCar|DELETE|/api/v1/car/model/{id}||
|updateCar|PATCH|/api/v1/car/model/{id}|string car_name|
