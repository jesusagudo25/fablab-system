<?php

    require_once '../app.php';

    $model = new Model();

    $model->query("CREATE TABLE user_role(
            role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL
        );");

    $model->query("CREATE TABLE provinces(
            province_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(60) NOT NULL
        );");

    $model->query("CREATE TABLE districts(
            district_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            province_id INT UNSIGNED NOT NULL,
            name VARCHAR(60) NOT NULL,

            FOREIGN KEY (province_id) REFERENCES provinces(province_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE townships(
            township_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            district_id INT UNSIGNED NOT NULL,
            name VARCHAR(60) NOT NULL,

            FOREIGN KEY (district_id) REFERENCES districts(district_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE users(
            user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            role_id INT UNSIGNED NOT NULL DEFAULT 1,
            name VARCHAR(60) NOT NULL,
            email VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(85) NOT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE,
            token VARCHAR(70) NOT NULL DEFAULT 'No definido',
            date_token DATE NULL,

            FOREIGN KEY (role_id) REFERENCES user_role(role_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE age_range(
                range_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL
            );");

    $model->query("CREATE TABLE customers(
            customer_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            document_type CHAR NOT NULL,
            document VARCHAR(60) UNIQUE NOT NULL,
            name VARCHAR(90) NOT NULL,
            range_id INT UNSIGNED NOT NULL,
            sex CHAR NOT NULL,
            email VARCHAR(60) UNIQUE NULL,
            telephone VARCHAR(70) UNIQUE NULL,
            province_id INT UNSIGNED NOT NULL,
            district_id INT UNSIGNED NOT NULL,
            township_id INT UNSIGNED NOT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE,
            
            FOREIGN KEY (range_id) REFERENCES age_range(range_id) ON DELETE CASCADE,
            FOREIGN KEY (province_id) REFERENCES provinces(province_id) ON DELETE CASCADE,
            FOREIGN KEY (district_id) REFERENCES districts(district_id) ON DELETE CASCADE,
            FOREIGN KEY (township_id) REFERENCES townships(township_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE reason_visits(
            reason_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(60) NOT NULL,
            isGroup BOOLEAN NOT NULL DEFAULT FALSE,
            time BOOLEAN NOT NULL DEFAULT TRUE,
            status BOOLEAN NOT NULL DEFAULT TRUE
        );");

    $model->query("CREATE TABLE visits(
            visit_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            reason_id INT UNSIGNED NOT NULL,
            date DATE NOT NULL DEFAULT (CURRENT_DATE),
            observation TEXT NULL,
            isAttended BOOLEAN NOT NULL DEFAULT FALSE,
            status BOOLEAN NOT NULL DEFAULT TRUE,
    
            FOREIGN KEY (reason_id) REFERENCES reason_visits(reason_id) ON DELETE CASCADE
        );");


    $model->query("CREATE TABLE customer_visit(
        customer_id INT UNSIGNED NOT NULL,
        visit_id INT UNSIGNED NOT NULL,

        PRIMARY KEY (customer_id, visit_id),
        FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
        FOREIGN KEY (visit_id) REFERENCES visits(visit_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE bookings(
            booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            document_type CHAR NOT NULL,
            document VARCHAR(60) NOT NULL,
            name VARCHAR(90) NOT NULL,
            reason_id INT UNSIGNED NOT NULL,
            isGroup BOOLEAN NOT NULL DEFAULT FALSE,
            date DATE NOT NULL,
            observation TEXT NULL,
    
            FOREIGN KEY (reason_id) REFERENCES reason_visits(reason_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE areas(
                area_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(60) NOT NULL,
                status BOOLEAN NOT NULL DEFAULT TRUE
                
            );");
    $model->query("CREATE TABLE visits_areas(
            visit_id INT UNSIGNED NOT NULL,
            area_id INT UNSIGNED NOT NULL,
            arrival_time TIME NOT NULL,
            departure_time TIME NOT NULL,

            PRIMARY KEY (visit_id, area_id),
            FOREIGN KEY (visit_id) REFERENCES visits(visit_id) ON DELETE CASCADE,
            FOREIGN KEY (area_id) REFERENCES areas(area_id) ON DELETE CASCADE
        );   ");
    $model->query("CREATE TABLE booking_area(
        booking_id INT UNSIGNED NOT NULL,
        area_id INT UNSIGNED NOT NULL,
        arrival_time TIME NOT NULL,
        departure_time TIME NOT NULL,

        PRIMARY KEY (booking_id, area_id),
        FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
        FOREIGN KEY (area_id) REFERENCES areas(area_id)
    );   ");        
    $model->query("CREATE TABLE observations(
            observation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            description TEXT NOT NULL,
            date DATE NOT NULL DEFAULT (CURRENT_DATE),
            
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);");
    $model->query("CREATE TABLE reports(
            report_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            year INT UNSIGNED NOT NULL,
            month VARCHAR(40) NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,

            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE invoices(
            invoice_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            receipt INT UNIQUE NULL,
            customer_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            date DATE NOT NULL DEFAULT (CURRENT_DATE),
            total DECIMAL(6,2) NOT NULL,
            
            FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        );");
    $model->query("CREATE TABLE event_category(
            category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(60) NOT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE
            
        );");



    $model->query("CREATE TABLE membership_plans(
            membership_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(60) NOT NULL,
            price DECIMAL(6,2) NOT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE
        );");

    $model->query("CREATE TABLE use_machine_type(
        type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    );");
    
    $model->query("CREATE TABLE use_machines(
            use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id INT UNSIGNED NOT NULL,
            area_id INT UNSIGNED NOT NULL,
            use_type_id INT UNSIGNED NOT NULL,
            manpower INT UNSIGNED NOT NULL,
            date_delivery DATE NOT NULL,
            total_price DECIMAL(6,2) NOT NULL,
            
            FOREIGN KEY (area_id) REFERENCES areas(area_id) ON DELETE CASCADE,
            FOREIGN KEY (use_type_id) REFERENCES use_machine_type(type_id) ON DELETE CASCADE,
            FOREIGN KEY (invoice_id) REFERENCES invoices (invoice_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE events(
            event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id INT UNSIGNED NOT NULL,
            name TEXT NOT NULL,
            initial_date DATE NOT NULL,
            final_date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            price DECIMAL(6,2) NOT NULL,
            expenses DECIMAL(6,2) NOT NULL,
            description_expenses TEXT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE,

            FOREIGN KEY (category_id) REFERENCES event_category(category_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE area_event(
            area_id INT UNSIGNED NOT NULL,
            event_id INT UNSIGNED NOT NULL,
            PRIMARY KEY (area_id, event_id),
            FOREIGN KEY (area_id) REFERENCES areas(area_id) ON DELETE CASCADE,
            FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
        );");

    $model->query("CREATE TABLE membership_invoices(
            id INT UNSIGNED AUTO_INCREMENT,
            invoice_id INT UNSIGNED NOT NULL,
            membership_id INT UNSIGNED NOT NULL,
            initial_date DATE NOT NULL,
            final_date DATE NOT NULL,
            price DECIMAL(6,2) NOT NULL,
            
            PRIMARY KEY (id,invoice_id),
	        FOREIGN KEY (invoice_id) REFERENCES invoices (invoice_id) ON DELETE CASCADE,
 	        FOREIGN KEY (membership_id) REFERENCES membership_plans (membership_id) ON DELETE CASCADE     
        );     ");

    $model->query("CREATE TABLE invoices_events(
            invoice_id INT UNSIGNED NOT NULL,
            event_id INT UNSIGNED NOT NULL,

            PRIMARY KEY (invoice_id, event_id),
	        FOREIGN KEY (invoice_id) REFERENCES invoices (invoice_id) ON DELETE CASCADE,
 	        FOREIGN KEY (event_id) REFERENCES events (event_id) ON DELETE CASCADE           
            
        );  ");

    $model->query("CREATE TABLE categories_components(
        category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
        
    );");

    /* --------------------- Inventory ---------------------- */

    $model->query("CREATE TABLE components(
        component_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        stock INT UNSIGNED NOT NULL,
        category_id INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE,
        
        FOREIGN KEY (category_id) REFERENCES categories_components(category_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE materials_mini_milling(
        material_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        stock INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    );");   

    $model->query("CREATE TABLE materials_laser(
        material_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(7,3) NOT NULL,
        width INT UNSIGNED NOT NULL,
        height INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
        
    );");   

    $model->query("CREATE TABLE vinilos(
        vinilo_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(7,3) NOT NULL,
        width INT UNSIGNED NOT NULL,
        height INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    );");

    $model->query("CREATE TABLE threads(
        thread_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        purchased_amount INT UNSIGNED NOT NULL,
        current_amount INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
        
    );");

    $model->query("CREATE TABLE filaments(
        filament_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        purchased_weight INT UNSIGNED NOT NULL,
        current_weight INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    );");

    $model->query("CREATE TABLE resins(
        resin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        purchased_weight INT UNSIGNED NOT NULL,
        current_weight INT UNSIGNED NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
        
    );");

    $model->query("CREATE TABLE softwares(
        software_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(60) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        expiration_date DATE NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
        
    );");

    /* ------------------- Use sales ----------------------- */

    $model->query("CREATE TABLE use_sale_embroiderer(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE
        
    );");

    $model->query("CREATE TABLE use_sale_threads(
        use_id INT UNSIGNED NOT NULL,
        thread_id INT UNSIGNED NOT NULL,
        number_stitches INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, thread_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_embroiderer(use_id) ON DELETE CASCADE,
        FOREIGN KEY (thread_id) REFERENCES threads(thread_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE use_sale_plotter(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE
    
    );");

    $model->query("CREATE TABLE use_sale_vinilos(
        use_id INT UNSIGNED NOT NULL,
        vinilo_id INT UNSIGNED NOT NULL,
        width INT UNSIGNED NOT NULL CHECK (width >= 4 ),
        height INT UNSIGNED NOT NULL CHECK (height >= 4 ),

        PRIMARY KEY (use_id, vinilo_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_plotter(use_id) ON DELETE CASCADE,
        FOREIGN KEY (vinilo_id) REFERENCES vinilos(vinilo_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE use_sale_electronics(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE

    );");

    $model->query("CREATE TABLE use_sale_components(
        use_id INT UNSIGNED NOT NULL,
        component_id INT UNSIGNED NOT NULL,
        number_components INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, component_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_electronics(use_id) ON DELETE CASCADE,
        FOREIGN KEY (component_id) REFERENCES components(component_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE use_sale_printer_filament(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE

    );");

    $model->query("CREATE TABLE use_sale_filament(
        use_id INT UNSIGNED NOT NULL,
        filament_id INT UNSIGNED NOT NULL,
        number_grams INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, filament_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_printer_filament(use_id) ON DELETE CASCADE,
        FOREIGN KEY (filament_id) REFERENCES filaments(filament_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE use_sale_printer_resin(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE

    );");

    $model->query("CREATE TABLE use_sale_resin(
        use_id INT UNSIGNED NOT NULL,
        resin_id INT UNSIGNED NOT NULL,
        number_grams INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, resin_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_printer_resin(use_id) ON DELETE CASCADE,
        FOREIGN KEY (resin_id) REFERENCES resins(resin_id) ON DELETE CASCADE
    );");

    $model->query("CREATE TABLE use_sale_laser(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE

    );");

    $model->query("CREATE TABLE use_sale_materials_laser(
        use_id INT UNSIGNED NOT NULL,
        material_id INT UNSIGNED NOT NULL,
        width INT UNSIGNED NOT NULL,
        height INT UNSIGNED NOT NULL,
        amount INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, material_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_laser(use_id) ON DELETE CASCADE,
        FOREIGN KEY (material_id) REFERENCES materials_laser(material_id) ON DELETE CASCADE
    );"); 

    $model->query("CREATE TABLE use_sale_mini_milling(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        number_minutes INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE

    );");

    $model->query("CREATE TABLE use_sale_materials_mini_milling(
        use_id INT UNSIGNED NOT NULL,
        material_id INT UNSIGNED NOT NULL,
        amount INT UNSIGNED NOT NULL,

        PRIMARY KEY (use_id, material_id),
        FOREIGN KEY (use_id) REFERENCES use_sale_mini_milling(use_id) ON DELETE CASCADE,
        FOREIGN KEY (material_id) REFERENCES materials_mini_milling(material_id) ON DELETE CASCADE
    );"); 

    $model->query("CREATE TABLE use_software_design(
        use_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        use_machine_id INT UNSIGNED NOT NULL,
        software_id INT UNSIGNED NOT NULL,
        number_hours INT UNSIGNED NOT NULL,
        base_cost DECIMAL(6,2) NOT NULL,

        FOREIGN KEY (use_machine_id) REFERENCES use_machines(use_id) ON DELETE CASCADE,
        FOREIGN KEY (software_id) REFERENCES softwares(software_id) ON DELETE CASCADE

    );");

    /*--------------------Inseccion de datos----------------  */

    $model->query("INSERT INTO user_role(name) VALUES('Secretaria'),('Operador'),('Administrador');");

    $model->query("INSERT INTO membership_plans(name,price) VALUES('Membresía: Pase de un día',5.00),('Membresía: 15 días',25.00),('Membresía: mensual',50.00);");

    $model->query("INSERT INTO event_category(name) VALUES('Capacitaciones'),('Workshop'),('Fab Lab Kids');");

    $model->query("INSERT INTO age_range(name) VALUES('18 o menos'),('19 - 26'),('27 - 35'),('36 - más');");

    $model->query("INSERT INTO reason_visits(name,isGroup, time) VALUES('Emprendimiento',0,1),('Proyecto académico',0,1),('Eventos',0,1),('Visita general/Tour',1,0), ('Servicios',0,0);");

    $model->query("INSERT INTO areas(name) VALUES('Electrónica'),('Mini Fresadora CNC'),('Láser CNC'),('Cortadora de Vinilo'),('Impresión 3D en filamento'),('Impresión 3D en resina'), ('Software de diseño'),('Bordadora CNC');");

    $model->query("INSERT INTO provinces (name) 
        VALUES ('Bocas del Toro'),
        ('Coclé'),
        ('Colón'),
        ('Chiriquí'),
        ('Darién'),
        ('Herrera'),
        ('Los Santos'),
        ('Panamá'),
        ('Veraguas'),
        ('Panamá Oeste');");

    $model->query("INSERT INTO districts (province_id,name) 
        VALUES (1,'Bocas del Toro'),
        (1,'Almirante'),
        (1,'Changuinola'),
        (1,'Chiriquí Grande'),
        (2,'Penonomé'),
        (2,'Aguadulce'),
        (2,'Antón'),
        (2,'La Pintada'),
        (2,'Natá'),
        (2,'Olá'),
        (3,'Colón'),
        (3,'Chagres'),
        (3,'Donoso'),
        (3,'Omar Torrijos Herrera'),
        (3,'Portobelo'),
        (3,'Santa Isabel'),
        (4,'David'),
        (4,'Alanje'),
        (4,'Barú'),
        (4,'Boquerón'),
        (4,'Boquete'),
        (4,'Bugaba'),
        (4,'Dolega'),
        (4,'Gualaca'),
        (4,'Remedios'),
        (4,'Renacimiento'),
        (4,'San Félix'),
        (4,'San Lorenzo'),
        (4,'Tierras Altas'),
        (4,'Tolé'),
        (5,'Chepigana'),
        (5,'Pinogana'),
        (5,'Santa Fe'),
        (6,'Chitré'),
        (6,'Las Minas'),
        (6,'Los Pozos'),
        (6,'Ocú'),
        (6,'Parita'),
        (6,'Pesé'),
        (6,'Santa María'),
        (7,'Las Tablas'),
        (7,'Guararé'),
        (7,'Los Santos'),
        (7,'Macaracas'),
        (7,'Pedasí'),
        (7,'Pocrí'),
        (7,'Tonosí'),
        (8,'Panamá'),
        (8,'Balboa'),
        (8,'Chepo'),
        (8,'Chimán'),
        (8,'San Miguelito'),
        (8,'Taboga'),
        (9,'Santiago'),
        (9,'Atalaya'),
        (9,'Calobre'),
        (9,'Cañazas'),
        (9,'La Mesa'),
        (9,'Las Palmas'),
        (9,'Mariato'),
        (9,'Montijo'),
        (9,'Rio de Jesús'),
        (9,'San Francisco'),
        (9,'Santa Fe'),
        (9,'Soná'),
        (10,'Arraiján'),
        (10,'Capira'),
        (10,'Chame'),
        (10,'La Chorrera'),
        (10,'San Carlos');");

    $model->query("INSERT INTO townships (district_id,name) 
        VALUES (1,'Bocas del Toro'),
        (1,'Cauchero'),
        (1,'Punta Laurel'),
        (1,'Tierra Oscura'),
        (2,'Puerto Almirante'),
        (2,'Barrio Francés'),
        (2,'Barriada Guaymí'),
        (2,'Nance de Riscó'),
        (2,'Valle de Aguas Arriba'),
        (2,'Valle de Riscó'),
        (3,'Chanquinola'),
        (3,'4 de Abril'),
        (3,'Finca 6'),
        (3,'Finca 30'),
        (3,'Finca 60'),
        (3,'El Silencio'),
        (3,'Guabito'),
        (3,'Teribe'),
        (3,'El Empalme'),
        (3,'Las Tablas'),
        (3,'Cochigró'),
        (3,'La Gloria'),
        (3,'Las Delicias'),
        (4,'Chiriquí Grande'),
        (4,'Miramar'),
        (4,'Punta Peña'),
        (4,'Punta Robalo'),
        (4,'Rambala'),
        (4,'Bajo Cedro'),
        (5,'Penonomé'),
        (5,'Cañaveral'),
        (5,'Coclé'),
        (5,'Chiguirí Arriba'),
        (5,'El Coco'),
        (5,'Pajonal'),
        (5,'Río Grande'),
        (5,'Río Indio'),
        (5,'Toabré'),
        (5,'Tulú'),
        (6,'Aguadulce'),
        (6,'El Cristo'),
        (6,'El Roble'),
        (6,'Pocrí'),
        (6,'Barrios Unidos'),
        (7,'Antón'),
        (7,'Cabuya'),
        (7,'El Chirú'),
        (7,'El Retiro'),
        (7,'El Valle'),
        (7,'Juan Díaz'),
        (7,'Río Hato'),
        (7,'San Juan de Dios'),
        (7,'Santa Rita'),
        (7,'Caballero'),
        (8,'La Pintada'),
        (8,'El Harino'),
        (8,'El Potrero'),
        (8,'Llano Grande'),
        (8,'Piedras Gordas'),
        (8,'Las Lomas'),
        (9,'Natá'),
        (9,'Capellanía'),
        (9,'El Caño'),
        (9,'Guzmán'),
        (9,'Las Huacas'),
        (9,'Toza'),
        (9,'Villarreal'),
        (10,'Olá'),
        (10,'El Copé'),
        (10,'El Palmar'),
        (10,'El Picacho'),
        (10,'La Pava'),
        (11,'Colón'),
        (11,'Buena Vista'),
        (11,'Cativá'),
        (11,'Ciricito'),
        (11,'Cristóbal'),
        (11,'Escobal'),
        (11,'Limón'),
        (11,'Nueva Providencia'),
        (11,'Puerto Pilón'),
        (11,'Sabanitas'),
        (11,'Salamanca'),
        (11,'San Juan'),
        (11,'Santa Rosa'),
        (12,'Nuevo Chagres'),
        (12,'Achiote'),
        (12,'El Guabo'),
        (12,'La Encantada'),
        (12,'Palmas Bellas'),
        (12,'Piña'),
        (12,'Salud'),
        (13,'Miguel de la Borda'),
        (13,'Coclé del Norte'),
        (13,'El Guásimo'),
        (13,'Gobea'),
        (13,'Río Indio'),
        (13,'San José del General'),
        (14,'San José del General'),
        (14,'Nueva Esperanza'),
        (14,'San Juan de Turbe'),
        (15,'Portobelo'),
        (15,'Cacique'),
        (15,'Puerto Lindo'),
        (15,'Isla Grande'),
        (15,'María Chiquita'),
        (16,'Palenque'),
        (16,'Cuango'),
        (16,'Miramar'),
        (16,'Nombre de Dios'),
        (16,'Palmira'),
        (16,'Playa Chiquita'),
        (16,'Santa Isabel'),
        (16,'Viento Frío'),
        (17,'David'),
        (17,'Bijagual'),
        (17,'Cochea'),
        (17,'Chiriquí'),
        (17,'Guacá'),
        (17,'Las Lomas'),
        (17,'Pedregal'),
        (17,'San Carlos'),
        (17,'San Pablo Nuevo'),
        (17,'San Pablo Viejo'),
        (18,'Alanje'),
        (18,'Divalá'),
        (18,'El Tejar'),
        (18,'Guarumal'),
        (18,'Palo Grande'),
        (18,'Querévalo'),
        (18,'Santo Tomás'),
        (18,'Canta Gallo'),
        (18,'Nuevo México'),
        (19,'Puerto Amuelles'),
        (19,'Limones'),
        (19,'Progreso'),
        (19,'Baco'),
        (19,'Rodolfo Aguilar Delgado'),
        (20,'Boquerón'),
        (20,'Bágala'),
        (20,'Cordillera'),
        (20,'Guabal'),
        (20,'Guayabal'),
        (20,'Paraíso'),
        (20,'Pedregal'),
        (20,'Tijeras'),
        (21,'Bajo Boquete'),
        (21,'Caldera'),
        (21,'Palmira'),
        (21,'Alto Boquete'),
        (21,'Jaramillo'),
        (21,'Los Naranjos'),
        (22,'La Concepción'),
        (22,'Aserrío de Gariché'),
        (22,'Bugaba'),
        (22,'Gómez'),
        (22,'La Estrella'),
        (22,'San Andrés'),
        (22,'Santa Marta'),
        (22,'Santa Rosa'),
        (22,'Santo Domingo'),
        (22,'Sortová'),
        (22,'El Bongo'),
        (23,'Dolega'),
        (23,'Dos Ríos'),
        (23,'Los Anastacios'),
        (23,'Potrerillos'),
        (23,'Potrerillos Abajo'),
        (23,'Rovira'),
        (23,'Tinajas'),
        (23,'Los Algarrobos'),
        (24,'Gualaca'),
        (24,'Hornito'),
        (24,'Los Ángeles'),
        (24,'Paja de Sombrero'),
        (24,'Rincón'),
        (25,'Remedios'),
        (25,'El Nancito'),
        (25,'El Porvenir'),
        (25,'El Puerto'),
        (25,'Santa Lucía'),
        (26,'Río Sereno'),
        (26,'Breñón'),
        (26,'Cañas Gordas'),
        (26,'Monte Lirio'),
        (26,'Plaza Caisán'),
        (26,'Santa Cruz'),
        (26,'Dominical'),
        (26,'Santa Clara'),
        (27,'Las Lajas'),
        (27,'Juay'),
        (27,'Lajas Adentro'),
        (27,'San Félix'),
        (27,'Santa Cruz'),
        (28,'Horconcitos'),
        (28,'Boca Chica'),
        (28,'Boca del Monte'),
        (28,'San Juan'),
        (28,'San Lorenzo'),
        (29,'Volcán'),
        (29,'Cerro Punta'),
        (29,'Cuesta de Piedra'),
        (29,'Nueva California'),
        (29,'Paso Ancho'),
        (30,'Tolé'),
        (30,'Bella Vista'),
        (30,'Cerro Viejo'),
        (30,'El Cristo'),
        (30,'Justo Fidel Palacios'),
        (30,'Lajas de Tolé'),
        (30,'Potrero de Caña'),
        (30,'Quebrada de Piedra'),
        (30,'Veladero'),
        (31,'La Palma'),
        (31,'Camogantí'),
        (31,'Chepigana'),
        (31,'Garachiné'),
        (31,'Jaqué'),
        (31,'Puerto Piña'),
        (31,'Río Congo'),
        (31,'Río Iglesias'),
        (31,'Sambú'),
        (31,'Setegantí'),
        (31,'Taimatí'),
        (31,'Tucutí'),
        (31,'Agua Fría'),
        (31,'Cucunatí'),
        (31,'Río Congo Arriba'),
        (31,'Santa Fe'),
        (32,'El Real de Santa María'),
        (32,'Boca de Cupé'),
        (32,'Paya'),
        (32,'Pinogana'),
        (32,'Púcuro'),
        (32,'Yape'),
        (32,'Yaviza'),
        (32,'Metetí'),
        (32,'Comarca Kuna de Wargandí'),
        (33,'Agua Fría'),
        (33,'Cucunatí'),
        (33,'Santa Fe'),
        (33,'Río Iglesias'),
        (33,'Río Congo'),
        (33,'Río Congo Arriba'),
        (33,'Zapallal'),
        (34,'Chitré'),
        (34,'La Arena'),
        (34,'Monagrillo'),
        (34,'Llano Bonito'),
        (34,'San Juan Bautista'),
        (35,'Las Minas'),
        (35,'Chepo'),
        (35,'Chumical'),
        (35,'El Toro'),
        (35,'Leones'),
        (35,'Quebrada del Rosario'),
        (35,'Quebrada El Ciprián'),
        (36,'Los Pozos'),
        (36,'Capurí'),
        (36,'El Calabacito'),
        (36,'El Cedro'),
        (36,'La Arena'),
        (36,'La Pitaloza'),
        (36,'Los Cerritos'),
        (36,'Los Cerros de Paja'),
        (36,'Las Llanas'),
        (37,'Ocú'),
        (37,'Cerro Largo'),
        (37,'Los Llanos'),
        (37,'Llano Grande'),
        (37,'Peñas Chatas'),
        (37,'El Tijera'),
        (37,'Menchaca'),
        (38,'Parita'),
        (38,'Cabuya'),
        (38,'Los Castillos'),
        (38,'Llano de la Cruz'),
        (38,'París'),
        (38,'Portobelillo'),
        (38,'Potuga'),
        (39,'Pesé'),
        (39,'Las Cabras'),
        (39,'El Pájaro'),
        (39,'El Barrero'),
        (39,'El Pedregoso'),
        (39,'El Ciruelo'),
        (39,'Sabanagrande'),
        (39,'Rincón Hondo'),
        (40,'Santa María'),
        (40,'Chupampa'),
        (40,'El Rincón'),
        (40,'El Limón'),
        (40,'Los Canelos'),
        (41,'Las Tablas'),
        (41,'Bajo Corral'),
        (41,'Bayano'),
        (41,'El Carate'),
        (41,'El Cocal'),
        (41,'El Manantial'),
        (41,'El Muñoz'),
        (41,'El Pedregoso'),
        (41,'La Laja'),
        (41,'La Miel'),
        (41,'La Palma'),
        (41,'La Tiza'),
        (41,'Las Palmitas'),
        (41,'Las Tablas Abajo'),
        (41,'Nuario'),
        (41,'Palmira'),
        (41,'Peña Blanca'),
        (41,'Río Hondo'),
        (41,'San José'),
        (41,'San Miguel'),
        (41,'Santo Domingo'),
        (41,'Sesteadero'),
        (41,'Valle Rico'),
        (41,'Vallerriquito'),
        (42,'Guararé'),
        (42,'El Espinal'),
        (42,'El Macano'),
        (42,'Guararé Arriba'),
        (42,'La Enea'),
        (42,'La Pasera'),
        (42,'Las Trancas'),
        (42,'Llano Abajo'),
        (42,'El Hato'),
        (42,'Perales'),
        (43,'La Villa de los Santos'),
        (43,'El Guásimo'),
        (43,'La Colorada'),
        (43,'La Espigadilla'),
        (43,'Las Cruces'),
        (43,'Las Guabas'),
        (43,'Los Ángeles'),
        (43,'Los Olivos'),
        (43,'Llano Largo'),
        (43,'Sabanagrande'),
        (43,'Santa Ana'),
        (43,'Tres Quebradas'),
        (43,'Agua Buena'),
        (43,'Villa Lourdes'),
        (44,'Macaracas'),
        (44,'Bahía Honda'),
        (44,'Bajos de Güera'),
        (44,'Corozal'),
        (44,'Chupá'),
        (44,'El Cedro'),
        (44,'Espino Amarillo'),
        (44,'La Mesa'),
        (44,'Las Palmas'),
        (44,'Llano de Piedra'),
        (44,'Mogollón'),
        (45,'Pedasí'),
        (45,'Los Asientos'),
        (45,'Mariabé'),
        (45,'Purio'),
        (45,'Oria Arriba'),
        (46,'Pocrí'),
        (46,'El Cañafístulo'),
        (46,'Lajamina'),
        (46,'Paraíso'),
        (46,'Paritilla'),
        (47,'Tonosí'),
        (47,'Altos de Güera'),
        (47,'Cañas'),
        (47,'El Bebedero'),
        (47,'El Cacao'),
        (47,'El Cortezo'),
        (47,'Flores'),
        (47,'Guánico'),
        (47,'Tronosa'),
        (47,'Cambutal'),
        (47,'Isla de Cañas'),
        (48,'Ciudad de Panamá'),
        (48,'Ancón'),
        (48,'Chilibre'),
        (48,'Las Cumbres'),
        (48,'Pacora'),
        (48,'San Martín Tocumen'),
        (48,'Las Mañanitas'),
        (48,'24 de Diciembre'),
        (48,'Alcalde Díaz'),
        (48,'Ernesto Córdoba Campos'),
        (49,'San Miguel'),
        (49,'La Ensenada'),
        (49,'La Esmeralda'),
        (49,'PortoLa Guineabelillo'),
        (49,'Pedro González'),
        (49,'Saboga'),
        (50,'Chepo'),
        (50,'Cañita'),
        (50,'Chepillo'),
        (50,'El Llano'),
        (50,'Las Margaritas'),
        (50,'Santa Cruz de Chinina'),
        (50,'Comara Kuna de Madungandi'),
        (50,'Tortí'),
        (51,'Chimán'),
        (51,'Brujas'),
        (51,'Gonzalo Vásquez'),
        (51,'Pásiga'),
        (51,'Unión Santeña'),
        (52,'Amelia Denis de Icaza'),
        (52,'Belisario Porras'),
        (52,'José Domingo Espinar'),
        (52,'Mateo Iturralde'),
        (52,'Victoriano Lorenzo'),
        (52,'Arnulfo Arias'),
        (52,'Belisario Frías'),
        (52,'Omar Torrijos'),
        (52,'Rufina Alfaro'),
        (53,'Taboga'),
        (53,'Otoque Oriente'),
        (53,'Otoque Occidente'),
        (54,'Santiago'),
        (54,'La Colorada'),
        (54,'La Peña'),
        (54,'La Raya de Santa María'),
        (54,'Ponuga'),
        (54,'San Pedro del Espino'),
        (54,'Canto del Llano'),
        (54,'Los Algarrobos'),
        (54,'Carlos Santana Ávila'),
        (54,'Edwin Fábrega'),
        (54,'San Martín de Porres'),
        (54,'Urracá'),
        (54,'Rodrigo Luque'),
        (54,'Santiago Este'),
        (54,'Nuevo Santiago'),
        (54,'Santiago Sur'),
        (55,'Atalaya'),
        (55,'El Barrito'),
        (55,'La Montañuela'),
        (55,'La Carrillo'),
        (55,'San Antonio'),
        (56,'Calobre'),
        (56,'Barnizal'),
        (56,'Chitra'),
        (56,'El Cocla'),
        (56,'El Potrero'),
        (56,'La Laguna'),
        (56,'La Raya de Calobre'),
        (56,'La Tetilla'),
        (56,'La Yeguada'),
        (56,'Las Guías'),
        (56,'Monjarás'),
        (56,'San José'),
        (57,'Cañazas'),
        (57,'Cerro de Plata'),
        (57,'El Picador'),
        (57,'Los Valles'),
        (57,'San José'),
        (57,'San Marcelo'),
        (57,'El Aromillo'),
        (57,'Las Cruces'),
        (58,'La Mesa'),
        (58,'Bisvalles'),
        (58,'Boró'),
        (58,'Llano Grande'),
        (58,'San Bartolo'),
        (58,'Los Milagros'),
        (59,'Las Palmas'),
        (59,'Cerro de Casa'),
        (59,'Corozal'),
        (59,'El María'),
        (59,'El Prado'),
        (59,'El Rincón'),
        (59,'Lolá'),
        (59,'Pixvae'),
        (59,'Puerto Vidal'),
        (59,'San Martín de Porres'),
        (59,'Viguí'),
        (59,'Zapotillo'),
        (60,'Mariato'),
        (60,'El Cacao'),
        (60,'Quebro'),
        (60,'Tebario'),
        (61,'Montijo'),
        (61,'Gobernadora'),
        (61,'La Garceana'),
        (61,'Leones'),
        (61,'Pilón'),
        (61,'Cébaco'),
        (61,'Costa Hermosa'),
        (61,'Unión del Norte'),
        (62,'Río de Jesús'),
        (62,'Las Huacas'),
        (62,'Los Castillos'),
        (62,'Utira'),
        (62,'Catorce de Noviembre'),
        (63,'San Francisco'),
        (63,'Corral Falso'),
        (63,'Los Hatillos'),
        (63,'Remance'),
        (63,'San Juan'),
        (63,'San José'),
        (64,'Santa Fe'),
        (64,'Calovébora'),
        (64,'El Alto'),
        (64,'El Cuay'),
        (64,'El Pantano'),
        (64,'Gatucito'),
        (64,'Río Luis'),
        (64,'Rubén Cantú'),
        (65,'Soná'),
        (65,'Bahía Honda'),
        (65,'Calidonia'),
        (65,'Cativé'),
        (65,'El Marañón'),
        (65,'Guarumal'),
        (65,'La Soledad'),
        (65,'Quebrada de Oro'),
        (65,'Río Grande'),
        (65,'Rodeo Viejo'),
        (66,'Arraiján'),
        (66,'Juan Demóstenes Arosemena'),
        (66,'Nuevo Emperador'),
        (66,'Santa Clara'),
        (66,'Veracruz'),
        (66,'Vista Alegre'),
        (66,'Burunga'),
        (66,'Cerro Silvestre'),
        (67,'Capira'),
        (67,'Caimito'),
        (67,'Campana'),
        (67,'Cermeño'),
        (67,'Cirí de Los Sotos'),
        (67,'Cirí Grande'),
        (67,'El Cacao'),
        (67,'La Trinidad'),
        (67,'Las Ollas Arriba'),
        (67,'Lídice'),
        (67,'Villa Carmen'),
        (67,'Villa Rosario'),
        (67,'Santa Rosa'),
        (68,'Chame'),
        (68,'Bejuco'),
        (68,'Buenos Aires'),
        (68,'Cabuya'),
        (68,'Chicá'),
        (68,'El Líbano'),
        (68,'Las Lajas'),
        (68,'Nueva Gorgona'),
        (68,'Punta Chame'),
        (68,'Sajalices'),
        (68,'Sorá'),
        (69,'Barrio Balboa'),
        (69,'Barrio Colón Amador'),
        (69,'Arosemena'),
        (69,'El Arado'),
        (69,'El Coco'),
        (69,'Feuillet'),
        (69,'Guadalupe'),
        (69,'Herrera'),
        (69,'Hurtado'),
        (69,'Iturralde'),
        (69,'La Represa'),
        (69,'Los Díaz'),
        (69,'Mendoza'),
        (69,'Obaldía'),
        (69,'Playa Leona'),
        (69,'Puerto Caimito'),
        (69,'Santa Rita'),
        (70,'San Carlos'),
        (70,'El Espino'),
        (70,'El Higo'),
        (70,'Guayabito'),
        (70,'La Ermita'),
        (70,'La Laguna'),
        (70,'Las Uvas'),
        (70,'Los Llanitos'),
        (70,'San José');");

    $passwordAdmin = password_hash('abc123', PASSWORD_BCRYPT);

    $insertarDatos = $model->prepare("INSERT INTO users(role_id,name,email,password) VALUES(3,'Rol Admin','admin@fablabsystem.com',:passwordOne), (1,'Rol Secretaria','secretaria@fablabsystem.com',:passwordTwo), (2,'Rol Operador','operador@fablabsystem.com',:passwordThree)");

    $insertarDatos->execute([
        ':passwordOne' => $passwordAdmin,
        ':passwordTwo' => $passwordAdmin,
        ':passwordThree' => $passwordAdmin,
    ]);