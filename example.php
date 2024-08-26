<?php

require 'FlashlearnAPI.php';

try {
    // Initialize the API wrapper
    $flashlearn = new FlashlearnAPI('https://flashlearn.nl/api');

    // Authenticate and get a token
    $token = $flashlearn->authenticate('your-email@example.com', 'your-password');
    echo "Authenticated successfully. Token: $token\n";

    // Fetch user profile
    $profile = $flashlearn->getProfile();
    echo "User Profile:\n";
    print_r($profile);

    // Fetch grades
    $grades = $flashlearn->getGrades();
    echo "Grades:\n";
    print_r($grades);

    // Fetch homework
    $homework = $flashlearn->getHomework();
    echo "Homework:\n";
    print_r($homework);

    // Fetch projects
    $projects = $flashlearn->getProjects();
    echo "Projects:\n";
    print_r($projects);

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
