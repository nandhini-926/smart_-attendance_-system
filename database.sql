CREATE DATABASE IF NOT EXISTS attendance;

USE attendance;

CREATE TABLE IF NOT EXISTS students (
    roll_no VARCHAR(20) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    class VARCHAR(20),
    Image_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(255) NOT NULL,
    branch VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE attendance_records (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    class_name VARCHAR(50) NOT NULL,
    status ENUM('Present', 'Absent', 'Late') NOT NULL,
    FOREIGN KEY (roll_no) REFERENCES students(roll_no)
);