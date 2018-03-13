Настройка проекта

1. Для настройки базы данных откройте файл DOC/config.example.php и укажите ваши 
настройки 
2.  Создайте две таблицы 
 CREATE TABLE clients 
  (
      id INT PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(255),
      email VARCHAR(255),
      phone VARCHAR(255)
  );   
  CREATE TABLE burgers
  (
      id INT PRIMARY KEY AUTO_INCREMENT,
      user_id INT,
      street VARCHAR(255),
      home VARCHAR(255),
      part VARCHAR(255),
      aprt VARCHAR(255),
      floor VARCHAR(255),
      comment VARCHAR(255),
      payment VARCHAR(255),
      callback VARCHAR(255)
  );
  
3. Запустите сервер   
4. Примеры SQL запросов в папке SQL
5. Приятного использования. 