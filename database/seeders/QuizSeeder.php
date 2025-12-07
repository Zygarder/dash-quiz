<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Clear existing data
        DB::table('quizzes')->truncate();
        DB::table('questions')->truncate();
        DB::table('answers')->truncate();
        
        //quiz categories
        $quizzes = [
            ['id' => 1, 'title' => 'COC1', 'description' => 'Certificate of Competency 1'],
            ['id' => 2, 'title' => 'COC2', 'description' => 'Certificate of Competency 2'],
            ['id' => 3, 'title' => 'COC3', 'description' => 'Certificate of Competency 3']
        ];

        // Questions
        $questions = [
            ['id' => 1, 'quiz_id' => 1, 'question_text' => 'What tool is used to test the power output of a PSU?', 'correct_option_id' => 1],
            ['id' => 2, 'quiz_id' => 1, 'question_text' => 'Which component is considered the brain of the computer?', 'correct_option_id' => 5],
            ['id' => 3, 'quiz_id' => 1, 'question_text' => 'What type of cable is used for SATA drives?', 'correct_option_id' => 9],
            ['id' => 4, 'quiz_id' => 1, 'question_text' => 'Which is a proper step before working inside a computer?', 'correct_option_id' => 13],
            ['id' => 5, 'quiz_id' => 1, 'question_text' => 'Which slot is used for a modern graphics card?', 'correct_option_id' => 17],
            ['id' => 6, 'quiz_id' => 1, 'question_text' => 'What does BIOS stand for?', 'correct_option_id' => 21],
            ['id' => 7, 'quiz_id' => 1, 'question_text' => 'What is the first device the BIOS checks when booting?', 'correct_option_id' => 25],
            ['id' => 8, 'quiz_id' => 1, 'question_text' => 'Which software is required after installing Windows?', 'correct_option_id' => 29],
            ['id' => 9, 'quiz_id' => 1, 'question_text' => 'What is the main purpose of thermal paste?', 'correct_option_id' => 33],
            ['id' => 10, 'quiz_id' => 1, 'question_text' => 'Which command is used to check system info in Windows?', 'correct_option_id' => 37],
            ['id' => 11, 'quiz_id' => 2, 'question_text' => 'What does IP stand for?', 'correct_option_id' => 41],
            ['id' => 12, 'quiz_id' => 2, 'question_text' => 'How many bits are in an IPv4 address?', 'correct_option_id' => 45],
            ['id' => 13, 'quiz_id' => 2, 'question_text' => 'Which command checks network connectivity?', 'correct_option_id' => 49],
            ['id' => 14, 'quiz_id' => 2, 'question_text' => 'What tool is used to crimp RJ45 connectors?', 'correct_option_id' => 53],
            ['id' => 15, 'quiz_id' => 2, 'question_text' => 'Which cable type is most commonly used in LANs?', 'correct_option_id' => 57],
            ['id' => 16, 'quiz_id' => 2, 'question_text' => 'Which network topology uses a central device?', 'correct_option_id' => 61],
            ['id' => 17, 'quiz_id' => 2, 'question_text' => 'Which device assigns IP addresses automatically?', 'correct_option_id' => 65],
            ['id' => 18, 'quiz_id' => 2, 'question_text' => 'What is the default subnet mask for Class C?', 'correct_option_id' => 69],
            ['id' => 19, 'quiz_id' => 2, 'question_text' => 'What protocol is used for domain name resolution?', 'correct_option_id' => 73],
            ['id' => 20, 'quiz_id' => 2, 'question_text' => 'Which IP address is reserved for loopback testing?', 'correct_option_id' => 77],
            ['id' => 21, 'quiz_id' => 3, 'question_text' => 'What is the proper tool for cleaning dust inside a computer?', 'correct_option_id' => 81],
            ['id' => 22, 'quiz_id' => 3, 'question_text' => 'Which component failure often causes a "no display" error?', 'correct_option_id' => 85],
            ['id' => 23, 'quiz_id' => 3, 'question_text' => 'Which command repairs corrupted system files in Windows?', 'correct_option_id' => 89],
            ['id' => 24, 'quiz_id' => 3, 'question_text' => 'Which is a sign of a failing hard drive?', 'correct_option_id' => 93],
            ['id' => 25, 'quiz_id' => 3, 'question_text' => 'What is the safe way to remove malware?', 'correct_option_id' => 97],
            ['id' => 26, 'quiz_id' => 3, 'question_text' => 'Which key combination is used to take a screenshot in Windows?', 'correct_option_id' => 104],
            ['id' => 27, 'quiz_id' => 3, 'question_text' => 'What is the main function of the PSU (Power Supply Unit)?', 'correct_option_id' => 105],
            ['id' => 28, 'quiz_id' => 3, 'question_text' => 'Which command displays the IP configuration on Windows?', 'correct_option_id' => 106],
            ['id' => 29, 'quiz_id' => 3, 'question_text' => 'Which device connects multiple networks together?', 'correct_option_id' => 114],
            ['id' => 30, 'quiz_id' => 3, 'question_text' => 'What is the safest way to handle a static-sensitive component?', 'correct_option_id' => 118]
        ];

        DB::table('questions')->insert($questions);

        // Answers
        $answers = [
            // Question 1
            ['id' => 1, 'question_id' => 1, 'answer_text' => 'Multimeter', 'is_correct' => 1],
            ['id' => 2, 'question_id' => 1, 'answer_text' => 'Crimping tool', 'is_correct' => 0],
            ['id' => 3, 'question_id' => 1, 'answer_text' => 'Loopback plug', 'is_correct' => 0],
            ['id' => 4, 'question_id' => 1, 'answer_text' => 'RJ45 tester', 'is_correct' => 0],

            // Question 2
            ['id' => 5, 'question_id' => 2, 'answer_text' => 'CPU', 'is_correct' => 1],
            ['id' => 6, 'question_id' => 2, 'answer_text' => 'RAM', 'is_correct' => 0],
            ['id' => 7, 'question_id' => 2, 'answer_text' => 'SSD', 'is_correct' => 0],
            ['id' => 8, 'question_id' => 2, 'answer_text' => 'PSU', 'is_correct' => 0],

            // Question 3
            ['id' => 9, 'question_id' => 3, 'answer_text' => '7-pin data cable', 'is_correct' => 1],
            ['id' => 10, 'question_id' => 3, 'answer_text' => '40-pin IDE ribbon', 'is_correct' => 0],
            ['id' => 11, 'question_id' => 3, 'answer_text' => 'Ethernet cable', 'is_correct' => 0],
            ['id' => 12, 'question_id' => 3, 'answer_text' => 'USB cable', 'is_correct' => 0],

            // Question 4
            ['id' => 13, 'question_id' => 4, 'answer_text' => 'Disconnect the power source', 'is_correct' => 1],
            ['id' => 14, 'question_id' => 4, 'answer_text' => 'Turn off monitor only', 'is_correct' => 0],
            ['id' => 15, 'question_id' => 4, 'answer_text' => 'Remove BIOS battery', 'is_correct' => 0],
            ['id' => 16, 'question_id' => 4, 'answer_text' => 'Format the hard drive', 'is_correct' => 0],

            // Question 5
            ['id' => 17, 'question_id' => 5, 'answer_text' => 'PCIe x16', 'is_correct' => 1],
            ['id' => 18, 'question_id' => 5, 'answer_text' => 'AGP', 'is_correct' => 0],
            ['id' => 19, 'question_id' => 5, 'answer_text' => 'PCI', 'is_correct' => 0],
            ['id' => 20, 'question_id' => 5, 'answer_text' => 'IDE', 'is_correct' => 0],

            // Question 6
            ['id' => 21, 'question_id' => 6, 'answer_text' => 'Basic Input Output System', 'is_correct' => 1],
            ['id' => 22, 'question_id' => 6, 'answer_text' => 'Binary Input Output Setup', 'is_correct' => 0],
            ['id' => 23, 'question_id' => 6, 'answer_text' => 'Basic Internal OS', 'is_correct' => 0],
            ['id' => 24, 'question_id' => 6, 'answer_text' => 'Boot Input Output System', 'is_correct' => 0],

            // Question 7
            ['id' => 25, 'question_id' => 7, 'answer_text' => 'Boot device', 'is_correct' => 1],
            ['id' => 26, 'question_id' => 7, 'answer_text' => 'Hard drive', 'is_correct' => 0],
            ['id' => 27, 'question_id' => 7, 'answer_text' => 'Monitor', 'is_correct' => 0],
            ['id' => 28, 'question_id' => 7, 'answer_text' => 'CPU', 'is_correct' => 0],

            // Question 8
            ['id' => 29, 'question_id' => 8, 'answer_text' => 'Driver software', 'is_correct' => 1],
            ['id' => 30, 'question_id' => 8, 'answer_text' => 'Word processor', 'is_correct' => 0],
            ['id' => 31, 'question_id' => 8, 'answer_text' => 'Video editor', 'is_correct' => 0],
            ['id' => 32, 'question_id' => 8, 'answer_text' => 'Firewall', 'is_correct' => 0],

            // Question 9
            ['id' => 33, 'question_id' => 9, 'answer_text' => 'Thermal paste', 'is_correct' => 1],
            ['id' => 34, 'question_id' => 9, 'answer_text' => 'Lubricant', 'is_correct' => 0],
            ['id' => 35, 'question_id' => 9, 'answer_text' => 'Silicone', 'is_correct' => 0],
            ['id' => 36, 'question_id' => 9, 'answer_text' => 'Tape', 'is_correct' => 0],

            // Question 10
            ['id' => 37, 'question_id' => 10, 'answer_text' => 'Systeminfo', 'is_correct' => 1],
            ['id' => 38, 'question_id' => 10, 'answer_text' => 'Ipconfig', 'is_correct' => 0],
            ['id' => 39, 'question_id' => 10, 'answer_text' => 'Ping', 'is_correct' => 0],
            ['id' => 40, 'question_id' => 10, 'answer_text' => 'Netstat', 'is_correct' => 0],

            // Question 11
            ['id' => 41, 'question_id' => 11, 'answer_text' => 'Internet Protocol', 'is_correct' => 1],
            ['id' => 42, 'question_id' => 11, 'answer_text' => 'Internal Process', 'is_correct' => 0],
            ['id' => 43, 'question_id' => 11, 'answer_text' => 'Input Port', 'is_correct' => 0],
            ['id' => 44, 'question_id' => 11, 'answer_text' => 'Intermediate Packet', 'is_correct' => 0],

            // Question 12
            ['id' => 45, 'question_id' => 12, 'answer_text' => '32 bits', 'is_correct' => 1],
            ['id' => 46, 'question_id' => 12, 'answer_text' => '16 bits', 'is_correct' => 0],
            ['id' => 47, 'question_id' => 12, 'answer_text' => '64 bits', 'is_correct' => 0],
            ['id' => 48, 'question_id' => 12, 'answer_text' => '128 bits', 'is_correct' => 0],

            // Question 13
            ['id' => 49, 'question_id' => 13, 'answer_text' => 'Ping', 'is_correct' => 1],
            ['id' => 50, 'question_id' => 13, 'answer_text' => 'Tracert', 'is_correct' => 0],
            ['id' => 51, 'question_id' => 13, 'answer_text' => 'Ipconfig', 'is_correct' => 0],
            ['id' => 52, 'question_id' => 13, 'answer_text' => 'Netstat', 'is_correct' => 0],

            // Question 14
            ['id' => 53, 'question_id' => 14, 'answer_text' => 'Crimping tool', 'is_correct' => 1],
            ['id' => 54, 'question_id' => 14, 'answer_text' => 'Multimeter', 'is_correct' => 0],
            ['id' => 55, 'question_id' => 14, 'answer_text' => 'Screwdriver', 'is_correct' => 0],
            ['id' => 56, 'question_id' => 14, 'answer_text' => 'RJ45 tester', 'is_correct' => 0],

            // Question 15
            ['id' => 57, 'question_id' => 15, 'answer_text' => 'Ethernet cable', 'is_correct' => 1],
            ['id' => 58, 'question_id' => 15, 'answer_text' => 'Coaxial', 'is_correct' => 0],
            ['id' => 59, 'question_id' => 15, 'answer_text' => 'Fiber optic', 'is_correct' => 0],
            ['id' => 60, 'question_id' => 15, 'answer_text' => 'USB cable', 'is_correct' => 0],

            // Question 16
            ['id' => 61, 'question_id' => 16, 'answer_text' => 'Star', 'is_correct' => 1],
            ['id' => 62, 'question_id' => 16, 'answer_text' => 'Bus', 'is_correct' => 0],
            ['id' => 63, 'question_id' => 16, 'answer_text' => 'Ring', 'is_correct' => 0],
            ['id' => 64, 'question_id' => 16, 'answer_text' => 'Mesh', 'is_correct' => 0],

            // Question 17
            ['id' => 65, 'question_id' => 17, 'answer_text' => 'DHCP server', 'is_correct' => 1],
            ['id' => 66, 'question_id' => 17, 'answer_text' => 'Switch', 'is_correct' => 0],
            ['id' => 67, 'question_id' => 17, 'answer_text' => 'Router', 'is_correct' => 0],
            ['id' => 68, 'question_id' => 17, 'answer_text' => 'Hub', 'is_correct' => 0],

            // Question 18
            ['id' => 69, 'question_id' => 18, 'answer_text' => '255.255.255.0', 'is_correct' => 1],
            ['id' => 70, 'question_id' => 18, 'answer_text' => '255.255.0.0', 'is_correct' => 0],
            ['id' => 71, 'question_id' => 18, 'answer_text' => '255.0.0.0', 'is_correct' => 0],
            ['id' => 72, 'question_id' => 18, 'answer_text' => '255.255.255.255', 'is_correct' => 0],

            // Question 19
            ['id' => 73, 'question_id' => 19, 'answer_text' => 'DNS', 'is_correct' => 1],
            ['id' => 74, 'question_id' => 19, 'answer_text' => 'HTTP', 'is_correct' => 0],
            ['id' => 75, 'question_id' => 19, 'answer_text' => 'FTP', 'is_correct' => 0],
            ['id' => 76, 'question_id' => 19, 'answer_text' => 'IP', 'is_correct' => 0],

            // Question 20
            ['id' => 77, 'question_id' => 20, 'answer_text' => '127.0.0.1', 'is_correct' => 1],
            ['id' => 78, 'question_id' => 20, 'answer_text' => '192.168.1.1', 'is_correct' => 0],
            ['id' => 79, 'question_id' => 20, 'answer_text' => '10.0.0.1', 'is_correct' => 0],
            ['id' => 80, 'question_id' => 20, 'answer_text' => '255.255.255.0', 'is_correct' => 0],

            // Question 21
            ['id' => 81, 'question_id' => 21, 'answer_text' => 'Compressed air', 'is_correct' => 1],
            ['id' => 82, 'question_id' => 21, 'answer_text' => 'Vacuum cleaner', 'is_correct' => 0],
            ['id' => 83, 'question_id' => 21, 'answer_text' => 'Brush', 'is_correct' => 0],
            ['id' => 84, 'question_id' => 21, 'answer_text' => 'Cloth', 'is_correct' => 0],

            // Question 22
            ['id' => 85, 'question_id' => 22, 'answer_text' => 'GPU', 'is_correct' => 1],
            ['id' => 86, 'question_id' => 22, 'answer_text' => 'RAM', 'is_correct' => 0],
            ['id' => 87, 'question_id' => 22, 'answer_text' => 'CPU', 'is_correct' => 0],
            ['id' => 88, 'question_id' => 22, 'answer_text' => 'PSU', 'is_correct' => 0],

            // Question 23
            ['id' => 89, 'question_id' => 23, 'answer_text' => 'SFC /scannow', 'is_correct' => 1],
            ['id' => 90, 'question_id' => 23, 'answer_text' => 'Chkdsk', 'is_correct' => 0],
            ['id' => 91, 'question_id' => 23, 'answer_text' => 'Format', 'is_correct' => 0],
            ['id' => 92, 'question_id' => 23, 'answer_text' => 'Diskpart', 'is_correct' => 0],

            // Question 24
            ['id' => 93, 'question_id' => 24, 'answer_text' => 'Strange noises', 'is_correct' => 1],
            ['id' => 94, 'question_id' => 24, 'answer_text' => 'Slow performance', 'is_correct' => 0],
            ['id' => 95, 'question_id' => 24, 'answer_text' => 'Frequent updates', 'is_correct' => 0],
            ['id' => 96, 'question_id' => 24, 'answer_text' => 'Blue screen', 'is_correct' => 0],

            // Question 25
            ['id' => 97, 'question_id' => 25, 'answer_text' => 'Use antivirus software', 'is_correct' => 1],
            ['id' => 98, 'question_id' => 25, 'answer_text' => 'Delete system32', 'is_correct' => 0],
            ['id' => 99, 'question_id' => 25, 'answer_text' => 'Format hard drive without backup', 'is_correct' => 0],
            ['id' => 100, 'question_id' => 25, 'answer_text' => 'Ignore the malware', 'is_correct' => 0],

            // Question 26
            ['id' => 101, 'question_id' => 26, 'answer_text' => 'Ctrl + C', 'is_correct' => 0],
            ['id' => 102, 'question_id' => 26, 'answer_text' => 'Alt + Tab', 'is_correct' => 0],
            ['id' => 104, 'question_id' => 26, 'answer_text' => 'PrtSc (Print Screen)', 'is_correct' => 1],
            ['id' => 105, 'question_id' => 26, 'answer_text' => 'Ctrl + Shift + Esc', 'is_correct' => 0],

            // Question 27
            ['id' => 106, 'question_id' => 27, 'answer_text' => 'Converts AC to DC and powers components', 'is_correct' => 1],
            ['id' => 107, 'question_id' => 27, 'answer_text' => 'Stores data', 'is_correct' => 0],
            ['id' => 108, 'question_id' => 27, 'answer_text' => 'Controls the CPU', 'is_correct' => 0],
            ['id' => 109, 'question_id' => 27, 'answer_text' => 'Cools down the system', 'is_correct' => 0],

            // Question 28
            ['id' => 110, 'question_id' => 28, 'answer_text' => 'ipconfig', 'is_correct' => 1],
            ['id' => 111, 'question_id' => 28, 'answer_text' => 'ping', 'is_correct' => 0],
            ['id' => 112, 'question_id' => 28, 'answer_text' => 'tracert', 'is_correct' => 0],
            ['id' => 113, 'question_id' => 28, 'answer_text' => 'netstat', 'is_correct' => 0],

            // Question 29
            ['id' => 114, 'question_id' => 29, 'answer_text' => 'Router', 'is_correct' => 1],
            ['id' => 115, 'question_id' => 29, 'answer_text' => 'Switch', 'is_correct' => 0],
            ['id' => 116, 'question_id' => 29, 'answer_text' => 'Hub', 'is_correct' => 0],
            ['id' => 117, 'question_id' => 29, 'answer_text' => 'Firewall', 'is_correct' => 0],

            // Question 30
            ['id' => 118, 'question_id' => 30, 'answer_text' => 'Use an anti-static wrist strap', 'is_correct' => 1],
            ['id' => 119, 'question_id' => 30, 'answer_text' => 'Handle components by the pins', 'is_correct' => 0],
            ['id' => 120, 'question_id' => 30, 'answer_text' => 'Place them on carpet', 'is_correct' => 0],
            ['id' => 121, 'question_id' => 30, 'answer_text' => 'Use a magnet', 'is_correct' => 0],


        ];
        $question_options = [
            // === COC1 ===
            ['question_id' => 1, 'option_text' => 'Multimeter'],
            ['question_id' => 1, 'option_text' => 'Crimping tool'],
            ['question_id' => 1, 'option_text' => 'Loopback plug'],
            ['question_id' => 1, 'option_text' => 'RJ45 tester'],

            ['question_id' => 2, 'option_text' => 'CPU'],
            ['question_id' => 2, 'option_text' => 'RAM'],
            ['question_id' => 2, 'option_text' => 'SSD'],
            ['question_id' => 2, 'option_text' => 'PSU'],

            ['question_id' => 3, 'option_text' => '7-pin data cable'],
            ['question_id' => 3, 'option_text' => '40-pin IDE ribbon'],
            ['question_id' => 3, 'option_text' => 'Ethernet cable'],
            ['question_id' => 3, 'option_text' => 'USB cable'],

            ['question_id' => 4, 'option_text' => 'Disconnect the power source'],
            ['question_id' => 4, 'option_text' => 'Turn off monitor only'],
            ['question_id' => 4, 'option_text' => 'Remove BIOS battery'],
            ['question_id' => 4, 'option_text' => 'Format the hard drive'],

            ['question_id' => 5, 'option_text' => 'PCIe x16'],
            ['question_id' => 5, 'option_text' => 'AGP'],
            ['question_id' => 5, 'option_text' => 'PCI'],
            ['question_id' => 5, 'option_text' => 'IDE'],

            ['question_id' => 6, 'option_text' => 'Basic Input Output System'],
            ['question_id' => 6, 'option_text' => 'Binary Input Output Setup'],
            ['question_id' => 6, 'option_text' => 'Basic Internal OS'],
            ['question_id' => 6, 'option_text' => 'Boot Input Output System'],

            ['question_id' => 7, 'option_text' => 'Boot device'],
            ['question_id' => 7, 'option_text' => 'Monitor'],
            ['question_id' => 7, 'option_text' => 'Printer'],
            ['question_id' => 7, 'option_text' => 'Network interface'],

            ['question_id' => 8, 'option_text' => 'Device Drivers'],
            ['question_id' => 8, 'option_text' => 'Video editing software'],
            ['question_id' => 8, 'option_text' => 'Game launcher'],
            ['question_id' => 8, 'option_text' => 'Media player'],

            ['question_id' => 9, 'option_text' => 'Transfer heat from CPU to heatsink'],
            ['question_id' => 9, 'option_text' => 'Store data'],
            ['question_id' => 9, 'option_text' => 'Clean components'],
            ['question_id' => 9, 'option_text' => 'Power the CPU fan'],

            ['question_id' => 10, 'option_text' => 'systeminfo'],
            ['question_id' => 10, 'option_text' => 'ipconfig'],
            ['question_id' => 10, 'option_text' => 'tasklist'],
            ['question_id' => 10, 'option_text' => 'chkdsk'],


            // === COC2 ===
            ['question_id' => 11, 'option_text' => 'Internet Protocol'],
            ['question_id' => 11, 'option_text' => 'Internal Process'],
            ['question_id' => 11, 'option_text' => 'Internet Provider'],
            ['question_id' => 11, 'option_text' => 'Input Packet'],

            ['question_id' => 12, 'option_text' => '32 bits'],
            ['question_id' => 12, 'option_text' => '16 bits'],
            ['question_id' => 12, 'option_text' => '64 bits'],
            ['question_id' => 12, 'option_text' => '48 bits'],

            ['question_id' => 13, 'option_text' => 'ping'],
            ['question_id' => 13, 'option_text' => 'ipconfig'],
            ['question_id' => 13, 'option_text' => 'tracert'],
            ['question_id' => 13, 'option_text' => 'netstat'],

            ['question_id' => 14, 'option_text' => 'Crimping tool'],
            ['question_id' => 14, 'option_text' => 'Multimeter'],
            ['question_id' => 14, 'option_text' => 'Screwdriver'],
            ['question_id' => 14, 'option_text' => 'Punch-down tool'],

            ['question_id' => 15, 'option_text' => 'UTP Cat5e/Cat6'],
            ['question_id' => 15, 'option_text' => 'Coaxial cable'],
            ['question_id' => 15, 'option_text' => 'Fiber ribbon'],
            ['question_id' => 15, 'option_text' => 'IDE cable'],

            ['question_id' => 16, 'option_text' => 'Star topology'],
            ['question_id' => 16, 'option_text' => 'Ring topology'],
            ['question_id' => 16, 'option_text' => 'Bus topology'],
            ['question_id' => 16, 'option_text' => 'Mesh topology'],

            ['question_id' => 17, 'option_text' => 'DHCP server'],
            ['question_id' => 17, 'option_text' => 'DNS server'],
            ['question_id' => 17, 'option_text' => 'Web server'],
            ['question_id' => 17, 'option_text' => 'FTP server'],

            ['question_id' => 18, 'option_text' => '255.255.255.0'],
            ['question_id' => 18, 'option_text' => '255.255.0.0'],
            ['question_id' => 18, 'option_text' => '255.0.0.0'],
            ['question_id' => 18, 'option_text' => '255.255.255.255'],

            ['question_id' => 19, 'option_text' => 'DNS'],
            ['question_id' => 19, 'option_text' => 'DHCP'],
            ['question_id' => 19, 'option_text' => 'TCP'],
            ['question_id' => 19, 'option_text' => 'FTP'],

            ['question_id' => 20, 'option_text' => '127.0.0.1'],
            ['question_id' => 20, 'option_text' => '192.168.0.1'],
            ['question_id' => 20, 'option_text' => '0.0.0.0'],
            ['question_id' => 20, 'option_text' => '255.255.255.255'],


            // === COC3 ===
            ['question_id' => 21, 'option_text' => 'Compressed air blower'],
            ['question_id' => 21, 'option_text' => 'Vacuum cleaner'],
            ['question_id' => 21, 'option_text' => 'Brush'],
            ['question_id' => 21, 'option_text' => 'Fan'],

            ['question_id' => 22, 'option_text' => 'RAM'],
            ['question_id' => 22, 'option_text' => 'USB port'],
            ['question_id' => 22, 'option_text' => 'Keyboard'],
            ['question_id' => 22, 'option_text' => 'LAN cable'],

            ['question_id' => 23, 'option_text' => 'sfc /scannow'],
            ['question_id' => 23, 'option_text' => 'format c:'],
            ['question_id' => 23, 'option_text' => 'diskpart clean'],
            ['question_id' => 23, 'option_text' => 'shutdown /r'],

            ['question_id' => 24, 'option_text' => 'Clicking noises'],
            ['question_id' => 24, 'option_text' => 'Fast boot'],
            ['question_id' => 24, 'option_text' => 'Bright display'],
            ['question_id' => 24, 'option_text' => 'High FPS'],

            ['question_id' => 25, 'option_text' => 'Run an antivirus scan'],
            ['question_id' => 25, 'option_text' => 'Delete system32'],
            ['question_id' => 25, 'option_text' => 'Unplug the monitor'],
            ['question_id' => 25, 'option_text' => 'Rename the virus file'],

            ['question_id' => 26, 'option_text' => 'Power cable & switch'],
            ['question_id' => 26, 'option_text' => 'Video driver'],
            ['question_id' => 26, 'option_text' => 'Mouse settings'],
            ['question_id' => 26, 'option_text' => 'Wallpaper'],

            ['question_id' => 27, 'option_text' => 'HWMonitor'],
            ['question_id' => 27, 'option_text' => 'Paint'],
            ['question_id' => 27, 'option_text' => 'Notepad'],
            ['question_id' => 27, 'option_text' => 'CMD'],

            ['question_id' => 28, 'option_text' => 'Preventive maintenance'],
            ['question_id' => 28, 'option_text' => 'Corrective maintenance'],
            ['question_id' => 28, 'option_text' => 'Predictive maintenance'],
            ['question_id' => 28, 'option_text' => 'System overhaul'],

            ['question_id' => 29, 'option_text' => 'Dust buildup'],
            ['question_id' => 29, 'option_text' => 'New GPU installed'],
            ['question_id' => 29, 'option_text' => 'Full storage'],
            ['question_id' => 29, 'option_text' => 'Updated drivers'],

            ['question_id' => 30, 'option_text' => 'CMOS battery'],
            ['question_id' => 30, 'option_text' => 'CPU fan'],
            ['question_id' => 30, 'option_text' => 'USB drive'],
            ['question_id' => 30, 'option_text' => 'SSD'],
        ];

        DB::table('question_options')->insert($question_options);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('answers')->insert($answers);
        DB::table('quizzes')->insert($quizzes);
    }
}
