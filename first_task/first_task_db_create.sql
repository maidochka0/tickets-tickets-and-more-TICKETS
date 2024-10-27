CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    event_date DATETIME NOT NULL,
    ticket_adult_price INT NOT NULL,
    ticket_adult_quantity INT NOT NULL,
    ticket_kid_price INT NOT NULL,
    ticket_kid_quantity INT NOT NULL,
    barcode VARCHAR(120) UNIQUE NOT NULL,
    equal_price INT NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);
