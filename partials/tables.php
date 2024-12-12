<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "hotelBookingSystem";

// Connecting to MySQL
$conn = mysqli_connect($servername, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Drop existing tables
$drop_tables = [
    "Notification", "Feedback", "Payment", "Booking", "Employees", "Hotel_Policies", "Services", "Rooms" , "Admin","Customer", "Login","Discount", "Room_Type", "Policy", "Hotel", "Location"
];

foreach ($drop_tables as $table) {
    $sql = "DROP TABLE IF EXISTS $table";
    mysqli_query($conn, $sql);
    /*if (mysqli_query($conn, $sql)) {
        echo "Table '$table' dropped successfully.<br>";
    } else {
        echo "Error dropping table '$table': " . mysqli_error($conn) . "<br>";
    }*/
}
//echo "<br><hr><br>";  
// Creating tables
$tables = [
    /*
    "Policy" => "CREATE TABLE IF NOT EXISTS Policy (
        P_ID INT AUTO_INCREMENT PRIMARY KEY,
        P_NAME VARCHAR(100) NOT NULL,
        P_DESC TEXT
    )",
    "Hotel_Policies" => "CREATE TABLE IF NOT EXISTS Hotel_Policies (
        HOTEL_HL_ID INT,
        POLICY_P_ID INT,
        PRIMARY KEY (HOTEL_HL_ID, POLICY_P_ID),
        FOREIGN KEY (HOTEL_HL_ID) REFERENCES Hotel(HL_ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (POLICY_P_ID) REFERENCES Policy(P_ID) ON DELETE CASCADE ON UPDATE CASCADE
    )",    
    "Notification" => "CREATE TABLE IF NOT EXISTS Notification (
        N_ID INT AUTO_INCREMENT PRIMARY KEY,
        MESSAGE TEXT,
        DATETIME DATETIME NOT NULL,
        BOOKING_B_ID INT,
        CUSTOMER_CNICNO BIGINT,  
        FOREIGN KEY (BOOKING_B_ID) REFERENCES Booking(B_ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (CUSTOMER_CNICNO) REFERENCES Customer(CNICNO) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    */
    "Services" => "CREATE TABLE IF NOT EXISTS Services (
        S_ID INT AUTO_INCREMENT PRIMARY KEY,
        S_NAME VARCHAR(50) NOT NULL,
        S_DESCRIPTION TEXT
    )",
    "Location" => "CREATE TABLE IF NOT EXISTS Location (
        L_ID INT AUTO_INCREMENT PRIMARY KEY,
        L_NAME VARCHAR(100) NOT NULL,
        STATE VARCHAR(100) NOT NULL
    )",
    "Hotel" => "CREATE TABLE IF NOT EXISTS Hotel (
        HL_ID INT AUTO_INCREMENT PRIMARY KEY,
        H_NAME VARCHAR(100) NOT NULL,
        PHONENO VARCHAR(15),
        EMAIL VARCHAR(100),
        DESCRIPTION TEXT,
        MANAGER VARCHAR(100),
        LOCATION_ID INT,
        H_IMG VARCHAR(40),
        FOREIGN KEY (LOCATION_ID) REFERENCES Location(L_ID) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "signin" => "CREATE TABLE IF NOT EXISTS SIGNIN (
        SIGNIN_ID INT AUTO_INCREMENT PRIMARY KEY, 
        USERNAME VARCHAR(100) UNIQUE,
        PASSWORD VARCHAR(100) NOT NULL,
        ROLE ENUM('admin', 'customer') NOT NULL
    )",
    "Admin" => "CREATE TABLE IF NOT EXISTS Admin (
        A_ID INT AUTO_INCREMENT PRIMARY KEY,
        A_NAME VARCHAR(100) NOT NULL,
        SIGNIN_ID INT,
        FOREIGN KEY (SIGNIN_ID) REFERENCES SIGNIN(SIGNIN_ID) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "Customer" => "CREATE TABLE IF NOT EXISTS Customer (
        CNICNO VARCHAR(15) PRIMARY KEY,
        C_FNAME VARCHAR(100) NOT NULL,
        C_LNAME VARCHAR(100) NOT NULL,
        C_EMAIL VARCHAR(100),
        C_PHONENO VARCHAR(15)
    )",
    "Employees" => "CREATE TABLE IF NOT EXISTS Employees (
        E_ID INT AUTO_INCREMENT PRIMARY KEY,
        E_NAME VARCHAR(100) NOT NULL,
        E_POST VARCHAR(50),
        HIRE_DATE DATE,
        PHONENO VARCHAR(15),
        HL_ID INT,
        FOREIGN KEY (HL_ID) REFERENCES Hotel(HL_ID) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "Rooms" => "CREATE TABLE IF NOT EXISTS Rooms (
        R_ID INT AUTO_INCREMENT PRIMARY KEY,
        R_NAME VARCHAR(50) NOT NULL,
        R_DESCRIPTION VARCHAR(255)
    )",
    "Hotel_Room" => "CREATE TABLE Hotel_Room (
        ROOM_NO INT NOT NULL,
        HL_ID INT NOT NULL,
        R_ID INT NOT NULL,
        PRICE DECIMAL(10, 2) NOT NULL,
        STATUS ENUM('Available', 'Occupied') DEFAULT 'Available',
        PRIMARY KEY (ROOM_NO, HL_ID),
        FOREIGN KEY (R_ID) REFERENCES Rooms(R_ID)
    )",
    "Discount" => "CREATE TABLE IF NOT EXISTS Discount (
        D_CODE VARCHAR(20) PRIMARY KEY,
        D_RATE DECIMAL(5,2) NOT NULL
    )",
    "Booking" => "CREATE TABLE IF NOT EXISTS Booking (
        B_ID INT AUTO_INCREMENT PRIMARY KEY,
        CHECK_IN DATETIME NOT NULL,
        CHECK_OUT DATETIME NOT NULL,
        HL_ID INT,
        D_CODE VARCHAR(20),
        CNICNO VARCHAR(15),  
        ROOM_NO INT,
        B_TIME DATETIME NOT NULL,
        FOREIGN KEY (HL_ID) REFERENCES Hotel(HL_ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (D_CODE) REFERENCES Discount(D_CODE) ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (ROOM_NO) REFERENCES Hotel_Room(ROOM_NO) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (CNICNO) REFERENCES Customer(CNICNO) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "Feedback" => "CREATE TABLE IF NOT EXISTS Feedback (
        F_ID INT AUTO_INCREMENT PRIMARY KEY,
        REVIEW TEXT,
        RATING DECIMAL(3,2),
        HL_ID INT,
        CNICNO VARCHAR(15),  
        FOREIGN KEY (HL_ID) REFERENCES Hotel(HL_ID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (CNICNO) REFERENCES Customer(CNICNO) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "Payment" => "CREATE TABLE IF NOT EXISTS Payment (
        P_ID INT AUTO_INCREMENT PRIMARY KEY,
        CARD_NO VARCHAR(20),
        P_TYPE VARCHAR(50),
        P_DATE DATETIME NOT NULL,
        AMOUNT DECIMAL(10,2),
        B_ID INT UNIQUE,
        FOREIGN KEY (B_ID) REFERENCES Booking(B_ID) ON DELETE CASCADE ON UPDATE CASCADE
    )",
    "Hotel_Services" => "CREATE TABLE IF NOT EXISTS Hotel_Services (
        HL_ID INT NOT NULL,
        S_ID INT NOT NULL,
        PRICE DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (HL_ID, S_ID)
    );" 
];


// Create each table
foreach ($tables as $table_name => $create_sql) {
    mysqli_query($conn, $create_sql);
    /*if (mysqli_query($conn, $create_sql)) {
        echo "Table '$table_name' created successfully.<br>";
    } else {
        echo "Error creating table '$table_name': " . mysqli_error($conn) . "<br>";
    }*/
}


// Insert values in each table
$inserts = [
    //services Table
    [
    "table" => "Services",
    "query" => "INSERT INTO Services (S_NAME, S_DESCRIPTION) VALUES
    ('Wi-Fi', 'High-speed internet access available in all rooms and public areas'),
    ('Breakfast', 'Complimentary breakfast provided to guests'),
    ('Laundry', 'Laundry and dry-cleaning services for guests'),
    ('Spa', 'Wellness and spa treatments including massages and therapies'),
    ('Gym', 'Fully-equipped gym with trainers available'),
    ('Swimming Pool', 'Access to the hotel’s swimming pool'),
    ('Airport Shuttle', 'Transportation to and from the airport'),
    ('Parking', 'Secure and free parking available'),
    ('Room Service', '24/7 in-room dining service'),
    ('Concierge', 'Assistance with travel and local recommendations');"
    ],
    // Location table
    [
    "table" => "Location",
    "query" => "INSERT INTO Location (L_NAME, STATE) VALUES
    ('Karachi', 'Sindh'),
    ('Hyderabad', 'Sindh'),
    ('Lahore', 'Punjab'),
    ('Murree', 'Punjab'),
    ('Multan', 'Punjab'),
    ('Rawalpindi', 'Punjab'),
    ('Faisalabad', 'Punjab'),
    ('Bahawalpur', 'Punjab'),
    ('Islamabad', 'Federal'),
    ('Quetta', 'Balochistan'),
    ('Peshawar', 'Khyber Pakhtunkhwa'),
    ('Skardu', 'Gilgit-Baltistan');"
    ], 
    // Hotel table
    [
        "table" => "Hotel",
    "query" => "INSERT INTO Hotel (H_NAME, PHONENO, EMAIL, DESCRIPTION, MANAGER, LOCATION_ID,H_IMG) VALUES
    ('Pearl Continental Karachi', '00211234567', 'pc.karachi@gmail.com', 'A luxury hotel in the heart of Karachi', 'Ali Khan', 1,'imgs/H1.jpg'),
    ('Mövenpick Hotel Karachi', '02197876543', 'movenpick.karachi@gmail.com', 'A world-class hotel located near Clifton', 'Omar Sheikh', 1,'imgs/H2.jpg'),
    ('Avari Towers Karachi', '02165478890', 'avari.karachi@gmail.com', 'High-end business and leisure hotel in Karachi', 'Khalid Ansari', 1,'imgs/H3.jpg'),
    ('Serena Hotel Islamabad', '05176546321', 'serena.isb@gmail.com', 'A premium hotel offering serene views in Islamabad', 'Fatima Ahmed', 3,'imgs/H4.jpg'),
    ('Marriott Hotel Islamabad', '05165463210', 'marriott.isb@gmail.com', 'Luxury accommodation in the heart of Islamabad', 'Ayesha Malik', 3,'imgs/H5.jpg'),
    ('Shangrila Resort Skardu', '058152342567', 'shangrila.skardu@gmail.com', 'A scenic resort nestled in Skardu', 'Rehan Gul', 6,'imgs/H6.jpg'),
    ('Faletti’s Hotel Lahore', '04234567896', 'fallettis.lahore@gmail.com', 'Historic hotel located in Lahore', 'Zara Tariq', 2,'imgs/H7.jpg'),
    ('Hilton Suites Lahore', '04267890123', 'hilton.lahore@gmail.com', 'Stylish hotel with modern amenities in Lahore', 'Mehreen Saeed', 2,'imgs/H8.jpg'),
    ('Ramada by Wyndham Multan', '06198762543', 'ramada.multan@gmail.com', 'Modern hotel providing comfort in Multan', 'Sana Yousuf', 4,'imgs/H9.jpg'),
    ('Hotel One Bahawalpur', '04625432167', 'hotelone.bwp@gmail.com', 'Affordable luxury in Bahawalpur', 'Amir Raza', 5,'imgs/H10.jpg'),
    ('Hyderabad Grand Hotel', '05223456789', 'grand.hyderabad@gmail.com', 'A comfortable and affordable hotel in Hyderabad', 'Nida Shah', 9,'imgs/H11.jpg'),
    ('Quetta Serena Hotel', '08126345678', 'serena.quetta@gmail.com', 'A luxury hotel offering the best of Balochistan hospitality', 'Imran Baloch', 5,'imgs/H12.jpg'),
    ('Peshawar Continental', '09172654321', 'continental.peshawar@gmail.com', 'Elegant accommodation with traditional Peshawar charm', 'Zain Ali', 4,'imgs/H13.jpg'),
    ('Sapphire Residency Faisalabad', '04156758901', 'sapphire.faisalabad@gmail.com', 'Affordable luxury with modern amenities in Faisalabad', 'Asma Riaz', 7,'imgs/H14.jpg'),
    ('Sarena Inn Murree', '05145678910', 'sarena.murree@gmail.com', 'A cozy retreat amidst the hills of Murree', 'Hassan Raza', 10,'imgs/H15.jpg');"
    ],
    // Employees table
    [
    "table" => "employees",
    "query" => "INSERT INTO Employees (E_NAME, E_POST, HIRE_DATE, PHONENO,HL_ID) VALUES
    ('Ali Khan', 'Manager', '2022-03-01', '00211000123', 1),
    ('Sara Ahmed', 'Receptionist', '2023-06-15', '00211000456', 1),
    ('Bilal Khan', 'Housekeeping Supervisor', '2021-07-20', '00211007890', 1),
    ('Omar Sheikh', 'Manager', '2021-04-15', '02197876544', 2),
    ('Nadia Hussain', 'Waitress', '2020-11-12', '02197876545', 2),
    ('Omar Iqbal', 'Chef', '2022-01-18', '02197876546', 2),
    ('Khalid Ansari', 'Manager', '2020-05-20', '02165478891', 3),
    ('Zara Khan', 'Front Desk Associate', '2022-09-05', '02165478892', 3),
    ('Mehmood Ali', 'Concierge', '2020-04-20', '05176546322', 4),
    ('Asma Bibi', 'Housekeeper', '2023-01-09', '05176546323', 4),
    ('Fatima Ahmed', 'Manager', '2021-10-11', '058152342568', 4),
    ('Ali Imran', 'Bell Boy', '2021-10-14', '058152342569', 6),
    ('Ayesha Malik', 'Manager', '2022-06-01', '04234567897', 5),
    ('Rehan Gul', 'Manager', '2023-03-23', '04267890124', 6),
    ('Ayesha Khan', 'Room Service', '2020-05-22', '04267890125', 8),
    ('Sami Raza', 'Chef', '2023-04-01', '06198762544', 9),
    ('Muneeb Ali', 'Security Guard', '2021-09-15', '04625432168', 10),
    ('Zara Tariq', 'Manager', '2020-08-05', '04625432169', 7),
    ('Amina Zahid', 'Waitress', '2021-02-13', '05223456790', 11),
    ('Faisal Nisar', 'Maintenance Technician', '2022-07-08', '05223456791', 11),
    ('Samiya Khurram', 'Chef', '2020-03-20', '08126345679', 12),
    ('Sabeen Bilal', 'Room Attendant', '2021-11-25', '08126345680', 12),
    ('Mehreen Saeed', 'Manager', '2021-06-17', '09172654322', 8),
    ('Hassan Shah', 'Receptionist', '2022-05-02', '09172654323', 13),
    ('Kiran Iqbal', 'Room Service', '2021-01-09', '04156758902', 14),
    ('Farhan Tariq', 'Security Guard', '2020-12-01', '04156758903', 14),
    ('Sana Yousuf', 'Manager', '2022-04-14', '04156758904', 9),
    ('Kashif Jamil', 'Concierge', '2021-07-01', '05145678911', 15),
    ('Amir Raza', 'Manager', '2024-04-14', '04156758904', 10),
    ('Nida Shah', 'Manager', '2021-05-11', '04145134904', 11),
    ('Imran Baloch', 'Manager', '2022-04-14', '04154467890', 12),
    ('Zain Ali', 'Manager', '2022-04-14', '09876543211', 13),
    ('Asma Riaz', 'Manager', '2022-04-14', '07896344533', 14),
    ('Hassan Raza', 'Manager', '2022-04-14', '0903456734', 15);"
    ],

    // Room_Type table
    [
    "table" => "room",
    "query" => "INSERT INTO Rooms (R_NAME,R_DESCRIPTION) VALUES
        ('Single Room','A room with one bed suitable for a single guest.'),
        ('Double Room', 'A room with two beds for two guests.'),
        ('Deluxe Room', 'A luxurious room with high-end amenities and a larger space.'),
        ('Suite','A spacious room with a separate living area and premium facilities.'),
        ('Executive Room', 'An upgraded room with executive-level amenities and a work desk.'),
        ('Presidential Suite','An ultra-luxurious suite with multiple rooms, a living room, and top-tier services.'),
        ('Family Room','A large room designed for families, featuring multiple beds and spacious amenities.'),
        ('Luxury Room', 'A high-end room with premium furnishings and exclusive services.'),
        ('Penthouse Suite',  'A lavish penthouse with panoramic views, private terrace, and luxury amenities.'),
        ('Garden View Room',  'A room with a view of the hotel garden, offering a peaceful stay.'),
        ('Ocean View Room', 'A room with an ocean view offering a scenic and relaxing experience.'),
        ('Poolside Room', 'A room located near the pool area, with easy access to the pool.'),
        ('Accessible Room',  'A room designed to be accessible for people with disabilities, with wide doors and safety features.'),
        ('Honeymoon Suite','A romantic suite designed for honeymoon couples with special amenities.'),
        ('Standard Room', 'A basic room offering essential amenities for a comfortable stay.');"
    ],
    // per hotel room data
    [
    "table" => "hotel_room",
    "query" => "INSERT INTO Hotel_Room (ROOM_NO, HL_ID, R_ID, PRICE, STATUS) VALUES
        (101, 1, 1, 12000, 'Available'), -- Pearl Continental Karachi, Single Room
        (102, 1, 2, 15000, 'Occupied'),  -- Pearl Continental Karachi, Double Room
        (103, 1, 3, 20000, 'Available'), -- Pearl Continental Karachi, Deluxe Room
        (201, 1, 4, 25000, 'Occupied'),  -- Pearl Continental Karachi, Suite
        (202, 1, 5, 30000, 'Available'), -- Pearl Continental Karachi, Executive Room
        (101, 2, 1, 11000, 'Available'), -- Mövenpick Hotel Karachi, Single Room
        (102, 2, 2, 14000, 'Occupied'),  -- Mövenpick Hotel Karachi, Double Room
        (103, 2, 3, 19000, 'Available'), -- Mövenpick Hotel Karachi, Deluxe Room
        (201, 2, 4, 24000, 'Available'), -- Mövenpick Hotel Karachi, Suite
        (202, 2, 5, 29000, 'Occupied'),  -- Mövenpick Hotel Karachi, Executive Room
        (101, 3, 1, 10000, 'Available'), -- Avari Towers Karachi, Single Room
        (102, 3, 2, 13000, 'Available'), -- Avari Towers Karachi, Double Room
        (103, 3, 3, 18000, 'Occupied'),  -- Avari Towers Karachi, Deluxe Room
        (201, 3, 4, 23000, 'Available'), -- Avari Towers Karachi, Suite
        (202, 3, 5, 28000, 'Available'), -- Avari Towers Karachi, Executive Room
        (101, 4, 1, 15000, 'Available'), -- Serena Hotel Islamabad, Single Room
        (102, 4, 2, 18000, 'Available'), -- Serena Hotel Islamabad, Double Room
        (103, 4, 3, 25000, 'Occupied'),  -- Serena Hotel Islamabad, Deluxe Room
        (201, 4, 4, 30000, 'Available'), -- Serena Hotel Islamabad, Suite
        (202, 4, 5, 35000, 'Occupied'),  -- Serena Hotel Islamabad, Executive Room
        (101, 10, 1, 12000, 'Available'), -- Bhawalpur Hotel Islamabad, Single Room
        (102, 10, 2, 20000, 'Available'), -- Bhawalpur Hotel Islamabad, Double Room
        (103, 10, 3, 29000, 'Occupied'),  -- Bhawalpur Hotel Islamabad, Deluxe Room
        (201, 10, 4, 31000, 'Available'), -- Bhawalpur Hotel Islamabad, Suite
        (202, 10, 5, 25000, 'Occupied');  -- Bhawalpur Hotel Islamabad, Executive Room"
    ],
    //costumer table
    [
        "table" => "customer",
        "query" => "INSERT INTO Customer (CNICNO, C_FNAME, C_LNAME, C_EMAIL, C_PHONENO) VALUES
        -- Customers for Pearl Continental Karachi (Hotel ID 1)
        ('12345-6789101-1', 'Ali', 'Raza', 'ali.razakpc@gmail.com', '03001234567'),
        ('12345-6790000-0', 'Sara', 'Khan', 'sara.khanpc@gmail.com', '03002345678'),
        
        -- Customers for Mövenpick Hotel Karachi (Hotel ID 2)
        ('22345-6789777-7', 'Tariq', 'Jamil', 'tariq.jamilmovenpick@gmail.com', '03003456789'),
        ('22345-6790787-8', 'Nida', 'Shah', 'nida.shahmovenpick@gmail.com', '03004567890'),
        
        -- Customers for Avari Towers Karachi (Hotel ID 3)
        ('32345-6789909-0', 'Ali', 'Usman', 'ali.usmanavari@gmail.com', '03005678901'),
        ('32345-6790999-9', 'Ayesha', 'Bukhari', 'ayesha.bukhariavari@gmail.com', '03006789012'),
        
        -- Customers for Serena Hotel Islamabad (Hotel ID 4)
        ('42345-6789123-4', 'Fahad', 'Iqbal', 'fahad.iqbalserena@gmail.com', '03007890123'),
        ('42345-6790432-1', 'Mariam', 'Saeed', 'mariam.saeedserena@gmail.com', '03008901234'),
        
        -- Customers for Marriott Hotel Islamabad (Hotel ID 5)
        ('52345-6789456-7', 'Hassan', 'Raza', 'hassan.razamarriott@gmail.com', '03009012345'),
        ('52345-6790765-4', 'Zainab', 'Iqbal', 'zainab.iqbalmarriott@gmail.com', '03010123456'),
        
        -- Customers for Shangrila Resort Skardu (Hotel ID 6)
        ('62345-6789989-9', 'Rehan', 'Gul', 'rehan.gulshangrila@gmail.com', '03011234567'),
        ('62345-6790099-9', 'Fatima', 'Khan', 'fatima.khanshangrila@gmail.com', '03012345678'),
        
        -- Customers for Faletti’s Hotel Lahore (Hotel ID 7)
        ('72345-6789456-6', 'Omar', 'Shah', 'omar.shahfallettis@gmail.com', '03013456789'),
        ('72345-6790788-8', 'Sana', 'Ahmad', 'sana.ahmadfallettis@gmail.com', '03014567890'),
        
        -- Customers for Hilton Suites Lahore (Hotel ID 8)
        ('82345-6789333-2', 'Usman', 'Ali', 'usman.alihilton@gmail.com', '03015678901'),
        ('82345-6790222-2', 'Rida', 'Saeed', 'rida.saeedhilton@gmail.com', '03016789012'),
        
        -- Customers for Ramada by Wyndham Multan (Hotel ID 9)
        ('92345-6789676-7', 'Ahmed', 'Khan', 'ahmed.khanramada@gmail.com', '03017890123'),
        ('92345-6790999-9', 'Hina', 'Mirza', 'hina.mirzaramada@gmail.com', '03018901234'),
        
        -- Customers for Hotel One Bahawalpur (Hotel ID 10)
        ('10234-5678000-0', 'Sajid', 'Ali', 'sajid.ali.hotelone@gmail.com', '03019012345'),
        ('10234-5679789-9', 'Sadaf', 'Jamil', 'sadaf.jamilhotelone@gmail.com', '03020123456'),
        
        -- Customers for Hyderabad Grand Hotel (Hotel ID 11)
        ('11234-5678677-7', 'Amir', 'Rehman', 'amir.rehman.hyderabad@gmail.com', '03021234567'),
        ('11234-5679123-3', 'Sana', 'Javed', 'sana.javed.hyderabad@gmail.com', '03022345678'),
        
        -- Customers for Quetta Serena Hotel (Hotel ID 12)
        ('12234-5678333-3', 'Bilal', 'Shah', 'bilal.shahquetta@gmail.com', '03023456789'),
        ('12234-5679222-2', 'Aisha', 'Aziz', 'aisha.azizquetta@gmail.com', '03024567890'),
        
        -- Customers for Peshawar Continental (Hotel ID 13)
        ('13234-5678111-1', 'Farhan', 'Iqbal', 'farhan.iqbalpeshawar@gmail.com', '03025678901'),
        ('13234-5679101-0', 'Ruqaya', 'Fayaz', 'ruqaya.fayazpeshawar@gmail.com', '03026789012'),
        
        -- Customers for Sapphire Residency Faisalabad (Hotel ID 14)
        ('14234-5678455-5', 'Wasiq', 'Shahzad', 'wasiq.shahzadsapphire@gmail.com', '03027890123'),
        ('14234-5679555-5', 'Noreen', 'Saeed', 'noreen.saeedsapphire@gmail.com', '03028901234'),
        
        -- Customers for Sarena Inn Murree (Hotel ID 15)
        ('15234-5678777-7', 'Shahzaib', 'Ilyas', 'shahzaib.ilyassarena@gmail.com', '03029012345'),
        ('15234-5679987-6', 'Kiran', 'Khan', 'kiran.khansarena@gmail.com', '03030123456');"
    ],
    // Feedback table
    [
        "table" => "feedback",
        "query" => "INSERT INTO Feedback (REVIEW, RATING, CNICNO, HL_ID) VALUES
            ('The experience was amazing! The staff were friendly and the amenities were top-notch.', 5, '12345-6789101-1', 1),
            ('Nice hotel but the room service could be improved.', 3, '12345-6790000-0', 1),
            ('A great stay overall! Excellent location and service.', 4, '22345-6789777-7', 2),
            ('The food could be better and the room was a bit noisy.', 3, '22345-6790787-8', 2),
            ('Very luxurious and clean. Would definitely stay again!', 5, '32345-6789909-0', 3),
            ('Had a good stay but the check-in process was a bit slow.', 4, '32345-6790999-9', 3),
            ('A beautiful hotel with wonderful views. Highly recommended!', 5, '42345-6789123-4', 4),
            ('Good experience but the breakfast options were limited.', 4, '42345-6790432-1', 4),
            ('Perfect stay! The rooms were clean and the staff were extremely helpful.', 5, '52345-6789456-7', 5),
            ('Overall a good stay, but the gym could use more equipment.', 4, '52345-6790765-4', 5),
            ('Amazing place, loved the peaceful environment. Highly recommend for nature lovers.', 5, '62345-6789989-9', 6),
            ('The location is great, but the room decor could be updated.', 4, '62345-6790099-9', 6),
            ('Good hotel with decent amenities. Value for money.', 4, '72345-6789456-6', 7),
            ('It was a pleasant stay, but the Wi-Fi was unreliable.', 3, '72345-6790788-8', 7),
            ('Best hotel stay ever. The staff were so welcoming and the room was perfect.', 5, '82345-6789333-2', 8),
            ('Decent hotel but the elevator was out of order during my stay.', 3, '82345-6790222-2', 8),
            ('Wonderful stay! The hotel was beautiful, and the staff were amazing.', 5, '92345-6789676-7', 9),
            ('Good hotel, but the air conditioning in my room wasn’t working properly.', 3, '92345-6790999-9', 9),
            ('Fantastic! One of the best experiences I’ve had at a hotel.', 5, '10234-5678000-0', 10),
            ('Hotel was great, but the swimming pool was too cold.', 3, '10234-5679789-9', 10);"
    ],
    [
        "table" => "Hotel_Services",
        "query" =>  "INSERT INTO Hotel_Services (HL_ID, S_ID, PRICE) VALUES
            -- Pearl Continental Karachi
            (1, 1, 0.00), -- Free Wi-Fi
            (1, 2, 0.00), -- Complimentary Breakfast
            (1, 4, 5000.00), -- Spa
            (1, 7, 1500.00), -- Airport Shuttle

            -- Mövenpick Hotel Karachi
            (2, 1, 0.00), -- Free Wi-Fi
            (2, 3, 1000.00), -- Laundry
            (2, 6, 0.00), -- Swimming Pool Access
            (2, 10, 0.00), -- Concierge Service

            -- Serena Hotel Islamabad
            (4, 1, 0.00), -- Free Wi-Fi
            (4, 2, 0.00), -- Complimentary Breakfast
            (4, 4, 6000.00), -- Spa
            (4, 5, 0.00), -- Gym Access
            (4, 8, 0.00), -- Parking

            -- Shangrila Resort Skardu
            (6, 1, 0.00), -- Free Wi-Fi
            (6, 7, 2500.00), -- Airport Shuttle
            (6, 6, 0.00), -- Swimming Pool Access

            -- Marriott Hotel Islamabad
            (5, 1, 0.00), -- Free Wi-Fi
            (5, 2, 0.00), -- Complimentary Breakfast
            (5, 9, 0.00), -- Room Service
            (5, 5, 0.00); -- Gym Access"
],
[
    "table" => "discount",
    "query" => "INSERT INTO Discount (D_CODE, D_RATE) VALUES
    ('NULL',0.00),
    ('WELCOME10',10.00), 
    ('LONGSTAY20',15.00), 
    ('JUSTMARRIED',20.00), 
    ('REWARDYOU',5.00),  
    ('FESTIVEFUN',25.00); "
],
// Booking table
[
    "table" => "booking",
    "query" => "INSERT INTO Booking (CHECK_IN, CHECK_OUT,HL_ID, D_CODE,CNICNO, ROOM_NO,B_TIME) VALUES
        ('2024-12-01 14:00:00', '2024-12-05 11:00:00', 1, 'NULL', '12345-6789101-1', 101, '2024-11-09 14:00:00'),
        ('2024-12-10 14:00:00', '2024-12-15 11:00:00', 1, 'NULL', '12345-6790000-0', 102,'2024-11-10 14:00:00'),
        ('2024-12-03 14:00:00', '2024-12-07 11:00:00', 1, 'NULL', '22345-6789777-7', 103,'2024-10-01 14:00:00'),
        ('2024-12-12 14:00:00', '2024-12-18 11:00:00', 1, 'NULL', '22345-6790787-8', 201, '2024-11-01 14:00:00'),
        ('2024-12-02 14:00:00', '2024-12-06 11:00:00', 1, 'NULL', '32345-6789909-0', 202, '2024-11-01 14:00:00'),
        ('2024-12-08 14:00:00', '2024-12-13 11:00:00', 2, 'NULL', '32345-6790999-9', 101,'2024-11-20 14:00:00'),
        ('2024-12-04 14:00:00', '2024-12-09 11:00:00', 2, 'NULL', '42345-6789123-4', 102,'2024-11-01 14:00:00'),
        ('2024-12-14 14:00:00', '2024-12-19 11:00:00', 2, 'NULL', '42345-6790432-1', 103,'2024-11-01 14:00:00'),
        ('2024-12-05 14:00:00', '2024-12-10 11:00:00', 2, 'NULL', '52345-6789456-7', 201,'2024-11-01 14:00:00'),
        ('2024-12-15 14:00:00', '2024-12-20 11:00:00', 2, 'NULL', '52345-6790765-4', 202,'2024-11-07 14:00:00'),
        ('2024-12-06 14:00:00', '2024-12-10 11:00:00', 3, 'NULL', '62345-6789989-9', 101,'2024-11-01 14:00:00'),
        ('2024-12-16 14:00:00', '2024-12-20 11:00:00', 3, 'NULL', '62345-6790099-9', 102,'2024-11-01 14:00:00'),
        ('2024-12-07 14:00:00', '2024-12-11 11:00:00', 3, 'NULL', '72345-6789456-6', 103,'2024-11-01 14:00:00'),
        ('2024-12-17 14:00:00', '2024-12-22 11:00:00', 3, 'NULL', '72345-6790788-8', 201,'2024-11-01 14:00:00'),
        ('2024-12-08 14:00:00', '2024-12-12 11:00:00', 3, 'NULL', '82345-6789333-2', 202,'2024-11-01 14:00:00'),
        ('2024-12-18 14:00:00', '2024-12-23 11:00:00', 4, 'NULL', '82345-6790222-2', 101,'2024-11-01 14:00:00'),
        ('2024-12-09 14:00:00', '2024-12-14 11:00:00', 4, 'NULL', '92345-6789676-7', 102,'2024-11-25 14:00:00'),
        ('2024-12-19 14:00:00', '2024-12-24 11:00:00', 4, 'NULL', '92345-6790999-9', 103,'2024-11-01 14:00:00'),
        ('2024-12-10 14:00:00', '2024-12-14 11:00:00', 4, 'NULL', '10234-5678000-0', 201,'2024-11-01 14:00:00'),
        ('2024-12-20 14:00:00', '2024-12-25 11:00:00', 4, 'NULL', '10234-5679789-9', 202,'2024-11-01 14:00:00'),
        ('2024-12-11 14:00:00', '2024-12-15 11:00:00', 10, 'NULL','11234-5678677-7', 101,'2024-11-01 14:00:00'),
        ('2024-12-21 14:00:00', '2024-12-25 11:00:00', 10, 'NULL', '11234-5679123-3', 102,'2024-11-01 14:00:00'),
        ('2024-12-12 14:00:00', '2024-12-16 11:00:00', 10, 'NULL', '12234-5678333-3', 103,'2024-11-22 14:00:00'),
        ('2024-12-22 14:00:00', '2024-12-26 11:00:00', 10, 'NULL', '12234-5679222-2', 201,'2024-11-01 14:00:00'),
        ('2024-12-13 14:00:00', '2024-12-17 11:00:00', 10, 'NULL', '13234-5678111-1', 202,'2024-11-01 14:00:00')"
]
];


// Execute each insert query
foreach ($inserts as $insert) {
    if (!mysqli_query($conn, $insert['query'])) {
        echo "Error inserting data into " . $insert['table'] . ": " . mysqli_error($conn) . "<br>";
    }
}


mysqli_close($conn);
?>