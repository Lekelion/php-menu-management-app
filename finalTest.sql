CREATE DATABASE IF NOT EXISTS phpFinal;
USE phpFinal;

CREATE TABLE IF NOT EXISTS menu_items (
    menuID INT AUTO_INCREMENT PRIMARY KEY,
    menuName VARCHAR(100) NOT NULL,
    menuPrice DECIMAL(6,2) NOT NULL,
    description TEXT,
    photo VARCHAR(255),
    vegan TINYINT(1) NOT NULL DEFAULT 0
);

INSERT INTO menu_items (menuName, menuPrice, description, photo, vegan) VALUES
('Margherita Pizza', 12.99, 'Classic pizza with fresh tomatoes, mozzarella, and basil.', 'margherita.png', 1),
('Pepperoni Pizza', 14.99, 'Spicy pepperoni with tomato sauce and mozzarella.', 'pepperoni.jpg', 0),
('Caesar Salad', 9.50, 'Romaine lettuce with Caesar dressing, croutons, and parmesan.', 'caesar.jpg', 0),
('Vegan Burger', 11.99, 'Plant-based patty with lettuce, tomato, and vegan mayo.', 'vegan_burger.jpg', 1),
('Grilled Salmon', 18.50, 'Fresh salmon fillet grilled to perfection with herbs.', 'salmon.jpg', 0),
('Spaghetti Bolognese', 13.99, 'Traditional Italian pasta with rich meat sauce.', 'spaghetti.jpg', 0),
('Mushroom Risotto', 14.50, 'Creamy risotto with mushrooms and parmesan.', 'mushroom_risotto.jpg', 1),
('Chicken Tacos', 10.99, 'Soft tortillas filled with seasoned chicken and veggies.', 'chicken_tacos.jpg', 0),
('Falafel Wrap', 8.99, 'Crispy falafel with lettuce, tomato, and tahini sauce.', 'falafel_wrap.jpg', 1),
('Chocolate Lava Cake', 6.50, 'Warm chocolate cake with gooey molten center.', 'lava_cake.jpg', 1);
