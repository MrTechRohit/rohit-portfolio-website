<?php
// api.php - Backend Controller & JSON Data Service
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Handle GET: Serve Resume Data & Asset Paths
if ($requestMethod === 'GET') {
    $portfolioData = [
        "name" => "Er. Rohit",
        "title" => "Full-Stack Web Developer & UI/UX Specialist",
        "summary" => "Specializing in single-page web applications, clean responsive layouts, and robust PHP/MySQL backends.",
        "avatar" => "assets/avatar.png",
        "logo" => "assets/logo.png",
		"education" => [
		[
                "degree" => "M.Tech in Computer Science & Engineering (CSE)",
                "institution" => "SRMIST Delhi-NCR campus in Modinagar",
                "year" => "2027",
                "details" => "Core coursework: Advanced Machine Learning, Deep Learning, Full-Stack Web Development, Cloud Computing, Advanced Data Structures and Algorithms, DevOps and CI/CD Pipelines, and Enterprise Software Architecture. Etc."
            ],
            [
                "degree" => "B.Tech in Computer Science & Engineering (CSE)",
                "institution" => "Delhi Institute of Engineering and Technology (DIET) Meerut",
				"Affiliation & Location:"=>"AKTU,Meerut, Uttar Pradesh",
                "year" => "2023",
                "details" => "Core coursework: Data Structures,NLP and Its Application, DBMS, Web Development, Machine Learning, and Database Management. Etc."
            ],
            [
                "degree" => "Senior Secondary Examination (Class 12th)",
				"year" => "2017",
                "institution" => "Shree Hans Inter College Muradnagar",
                           "details" => "Science Stream with Physics, Chemistry, and Mathematics."
            ]
        ],
        "skills" => [
            "HTML5 & CSS3 (Flexbox/Grid)",
            "Vanilla JavaScript (ES6+, Fetch API)",
            "PHP (OOP & PDO)",
            "MySQL Database Design",
            "RESTful API Development",
            "Single Page Application (SPA) Architecture",
            "UI/UX Responsive Design",
			"Full-Stack Development ",
            "Git & Version Control",
			"C and C++, Python, JAVA (Basic)"
        ],
"projects" => [
            [
                "title" => "Hospital Management System",
                "tech" => "PHP, MySQL, PDO, CSS",
                "desc" => "Full CRUD application featuring doctor scheduling, patient logs, and real-time conflict prevention for appointments."
            ],
            [
                "title" => "E-Commerce Management System",
                "tech" => "PHP, MySQL, JavaScript",
                "desc" => "Live catalog with multi-criteria filtering, shopping cart sessions, and atomic real-time stock deductions."
            ],
            [
                "title" => "Live Weather SPA",
                "tech" => "JavaScript, Fetch API, CSS Grid",
                "desc" => "Asynchronous single-page weather dashboard consuming public REST APIs without page reloads."
            ],
            [
                "title" => "Library Book & Member Management System",
                "tech" => "PHP, MySQL, PDO, Sessions",
                "desc" => "Book inventory tracking, role-based member logins, borrow limits enforcement, ISBN format validation, and tabular activity reports."
            ]
        ]
    ];

    echo json_encode(["status" => "success", "data" => $portfolioData]);
    exit;
}

// Handle POST: Receive and Process Contact Form Submission
if ($requestMethod === 'POST') {
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    $name    = htmlspecialchars(trim($data['name'] ?? ''));
    $email   = htmlspecialchars(trim($data['email'] ?? ''));
    $message = htmlspecialchars(trim($data['message'] ?? ''));

    // Server-Side Validations
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Please fill out all required fields."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid email address format."]);
        exit;
    }

    // Persist Contact Message to Local Server Log
    $logEntry = "[" . date('Y-m-d H:i:s') . "] From: $name ($email) | Message: $message" . PHP_EOL;
    file_put_contents("messages.txt", $logEntry, FILE_APPEND | LOCK_EX);

    echo json_encode([
        "status" => "success", 
        "message" => "Thank you, {$name}! Your message has been sent successfully."
    ]);
    exit;
}

// Any other HTTP Method
http_response_code(405);
echo json_encode(["status" => "error", "message" => "Method not permitted."]);