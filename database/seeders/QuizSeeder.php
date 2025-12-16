<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        //50 new questions, 17 coc1, 17 coc2, 16 coc3
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('quizzes')->truncate();
        DB::table('questions')->truncate();
        DB::table('answers')->truncate();
        DB::table('question_options')->truncate();
        
        // quiz categories
        $quizzes = [
            ['id' => 1, 'title' => 'COC1', 'description' => 'Certificate of Competency 1 (Hardware & OS)'],
            ['id' => 2, 'title' => 'COC2', 'description' => 'Certificate of Competency 2 (Networking)'],
            ['id' => 3, 'title' => 'COC3', 'description' => 'Certificate of Competency 3 (Troubleshooting)']
        ];

        $questions = [
            // === COC1 (Hardware/Installation) ===
            ['id' => 1, 'quiz_id' => 1, 'question_text' => 'What tool is used to test the power output of a PSU?', 'correct_option_id' => 1],
            ['id' => 2, 'quiz_id' => 1, 'question_text' => 'Which component is considered the brain of the computer?', 'correct_option_id' => 5],
            ['id' => 3, 'quiz_id' => 1, 'question_text' => 'What type of cable is used for SATA drives?', 'correct_option_id' => 9],
            ['id' => 4, 'quiz_id' => 1, 'question_text' => 'Which is a proper step before working inside a computer?', 'correct_option_id' => 13],
            ['id' => 5, 'quiz_id' => 1, 'question_text' => 'Which slot is used for a modern graphics card?', 'correct_option_id' => 17],
            ['id' => 6, 'quiz_id' => 1, 'question_text' => 'What does BIOS stand for?', 'correct_option_id' => 21],
            ['id' => 7, 'quiz_id' => 1, 'question_text' => 'What is the first device the BIOS checks when booting?', 'correct_option_id' => 25],
            ['id' => 8, 'quiz_id' => 1, 'question_text' => 'Which software is required after installing Windows to make hardware work?', 'correct_option_id' => 29],
            ['id' => 9, 'quiz_id' => 1, 'question_text' => 'What is the main purpose of thermal paste?', 'correct_option_id' => 33],
            ['id' => 10, 'quiz_id' => 1, 'question_text' => 'Which command is used to check system info in Windows?', 'correct_option_id' => 37],
            ['id' => 11, 'quiz_id' => 1, 'question_text' => 'Which memory type is volatile (loses data when power is off)?', 'correct_option_id' => 41],
            ['id' => 12, 'quiz_id' => 1, 'question_text' => 'What is the standard partitioning scheme for modern Windows UEFI?', 'correct_option_id' => 45],
            ['id' => 13, 'quiz_id' => 1, 'question_text' => 'Which file system is default for Windows installations?', 'correct_option_id' => 49],
            ['id' => 14, 'quiz_id' => 1, 'question_text' => 'Which connector transmits both high-definition video and audio?', 'correct_option_id' => 53],
            ['id' => 15, 'quiz_id' => 1, 'question_text' => 'What does ESD stand for?', 'correct_option_id' => 57],
            ['id' => 16, 'quiz_id' => 1, 'question_text' => 'Which device is an input device?', 'correct_option_id' => 61],
            ['id' => 17, 'quiz_id' => 1, 'question_text' => 'What form factor is the most common for desktop motherboards?', 'correct_option_id' => 65],

            // === COC2 (Networking) ===
            ['id' => 18, 'quiz_id' => 2, 'question_text' => 'What does IP stand for?', 'correct_option_id' => 69],
            ['id' => 19, 'quiz_id' => 2, 'question_text' => 'How many bits are in an IPv4 address?', 'correct_option_id' => 73],
            ['id' => 20, 'quiz_id' => 2, 'question_text' => 'Which command checks network connectivity?', 'correct_option_id' => 77],
            ['id' => 21, 'quiz_id' => 2, 'question_text' => 'What tool is used to crimp RJ45 connectors?', 'correct_option_id' => 81],
            ['id' => 22, 'quiz_id' => 2, 'question_text' => 'Which cable type is most commonly used in LANs?', 'correct_option_id' => 85],
            ['id' => 23, 'quiz_id' => 2, 'question_text' => 'Which network topology uses a central device?', 'correct_option_id' => 89],
            ['id' => 24, 'quiz_id' => 2, 'question_text' => 'Which device assigns IP addresses automatically?', 'correct_option_id' => 93],
            ['id' => 25, 'quiz_id' => 2, 'question_text' => 'What is the default subnet mask for Class C?', 'correct_option_id' => 97],
            ['id' => 26, 'quiz_id' => 2, 'question_text' => 'What protocol is used for domain name resolution?', 'correct_option_id' => 101],
            ['id' => 27, 'quiz_id' => 2, 'question_text' => 'Which IP address is reserved for loopback testing?', 'correct_option_id' => 105],
            ['id' => 28, 'quiz_id' => 2, 'question_text' => 'What does MAC address stand for?', 'correct_option_id' => 109],
            ['id' => 29, 'quiz_id' => 2, 'question_text' => 'Which device operates at Layer 2 (Data Link) and filters traffic?', 'correct_option_id' => 113],
            ['id' => 30, 'quiz_id' => 2, 'question_text' => 'Which wireless standard is known as Wi-Fi 6?', 'correct_option_id' => 117],
            ['id' => 31, 'quiz_id' => 2, 'question_text' => 'What is the color of the first pin in T568B standard?', 'correct_option_id' => 121],
            ['id' => 32, 'quiz_id' => 2, 'question_text' => 'What type of cable connects two similar devices (e.g., PC to PC)?', 'correct_option_id' => 125],
            ['id' => 33, 'quiz_id' => 2, 'question_text' => 'Which command traces the path of packets to a destination?', 'correct_option_id' => 129],
            ['id' => 34, 'quiz_id' => 2, 'question_text' => 'What does WAN stand for?', 'correct_option_id' => 133],

            // === COC3 (Troubleshooting/Maintenance) ===
            ['id' => 35, 'quiz_id' => 3, 'question_text' => 'What is the proper tool for cleaning dust inside a computer?', 'correct_option_id' => 137],
            ['id' => 36, 'quiz_id' => 3, 'question_text' => 'Which component failure often causes a "no display" error?', 'correct_option_id' => 141],
            ['id' => 37, 'quiz_id' => 3, 'question_text' => 'Which command repairs corrupted system files in Windows?', 'correct_option_id' => 145],
            ['id' => 38, 'quiz_id' => 3, 'question_text' => 'Which is a sign of a failing hard drive?', 'correct_option_id' => 149],
            ['id' => 39, 'quiz_id' => 3, 'question_text' => 'What is the safe way to remove malware?', 'correct_option_id' => 153],
            ['id' => 40, 'quiz_id' => 3, 'question_text' => 'Which key combination is used to take a screenshot in Windows?', 'correct_option_id' => 157],
            ['id' => 41, 'quiz_id' => 3, 'question_text' => 'What is the main function of the PSU?', 'correct_option_id' => 161],
            ['id' => 42, 'quiz_id' => 3, 'question_text' => 'Which command displays the IP configuration on Windows?', 'correct_option_id' => 165],
            ['id' => 43, 'quiz_id' => 3, 'question_text' => 'Which device connects multiple networks together?', 'correct_option_id' => 169],
            ['id' => 44, 'quiz_id' => 3, 'question_text' => 'What is the safest way to handle a static-sensitive component?', 'correct_option_id' => 173],
            ['id' => 45, 'quiz_id' => 3, 'question_text' => 'Which tool allows you to close unresponsive programs?', 'correct_option_id' => 177],
            ['id' => 46, 'quiz_id' => 3, 'question_text' => 'What does a CMOS battery failure usually cause?', 'correct_option_id' => 181],
            ['id' => 47, 'quiz_id' => 3, 'question_text' => 'What is the purpose of "Safe Mode"?', 'correct_option_id' => 185],
            ['id' => 48, 'quiz_id' => 3, 'question_text' => 'Which utility optimizes the performance of a hard drive?', 'correct_option_id' => 189],
            ['id' => 49, 'quiz_id' => 3, 'question_text' => 'What indicates a POST failure?', 'correct_option_id' => 193],
            ['id' => 50, 'quiz_id' => 3, 'question_text' => 'What should you do before upgrading the BIOS?', 'correct_option_id' => 197],
        ];

        DB::table('questions')->insert($questions);

        // Answers
        $answers = [
            // Q1
            ['id' => 1, 'question_id' => 1, 'answer_text' => 'Multimeter', 'is_correct' => 1],
            ['id' => 2, 'question_id' => 1, 'answer_text' => 'Crimping tool', 'is_correct' => 0],
            ['id' => 3, 'question_id' => 1, 'answer_text' => 'Loopback plug', 'is_correct' => 0],
            ['id' => 4, 'question_id' => 1, 'answer_text' => 'RJ45 tester', 'is_correct' => 0],
            // Q2
            ['id' => 5, 'question_id' => 2, 'answer_text' => 'CPU', 'is_correct' => 1],
            ['id' => 6, 'question_id' => 2, 'answer_text' => 'RAM', 'is_correct' => 0],
            ['id' => 7, 'question_id' => 2, 'answer_text' => 'SSD', 'is_correct' => 0],
            ['id' => 8, 'question_id' => 2, 'answer_text' => 'PSU', 'is_correct' => 0],
            // Q3
            ['id' => 9, 'question_id' => 3, 'answer_text' => '7-pin data cable', 'is_correct' => 1],
            ['id' => 10, 'question_id' => 3, 'answer_text' => '40-pin IDE ribbon', 'is_correct' => 0],
            ['id' => 11, 'question_id' => 3, 'answer_text' => 'Ethernet cable', 'is_correct' => 0],
            ['id' => 12, 'question_id' => 3, 'answer_text' => 'USB cable', 'is_correct' => 0],
            // Q4
            ['id' => 13, 'question_id' => 4, 'answer_text' => 'Disconnect the power source', 'is_correct' => 1],
            ['id' => 14, 'question_id' => 4, 'answer_text' => 'Turn off monitor only', 'is_correct' => 0],
            ['id' => 15, 'question_id' => 4, 'answer_text' => 'Remove BIOS battery', 'is_correct' => 0],
            ['id' => 16, 'question_id' => 4, 'answer_text' => 'Format the hard drive', 'is_correct' => 0],
            // Q5
            ['id' => 17, 'question_id' => 5, 'answer_text' => 'PCIe x16', 'is_correct' => 1],
            ['id' => 18, 'question_id' => 5, 'answer_text' => 'AGP', 'is_correct' => 0],
            ['id' => 19, 'question_id' => 5, 'answer_text' => 'PCI', 'is_correct' => 0],
            ['id' => 20, 'question_id' => 5, 'answer_text' => 'IDE', 'is_correct' => 0],
            // Q6
            ['id' => 21, 'question_id' => 6, 'answer_text' => 'Basic Input Output System', 'is_correct' => 1],
            ['id' => 22, 'question_id' => 6, 'answer_text' => 'Binary Input Output Setup', 'is_correct' => 0],
            ['id' => 23, 'question_id' => 6, 'answer_text' => 'Basic Internal OS', 'is_correct' => 0],
            ['id' => 24, 'question_id' => 6, 'answer_text' => 'Boot Input Output System', 'is_correct' => 0],
            // Q7
            ['id' => 25, 'question_id' => 7, 'answer_text' => 'Boot device', 'is_correct' => 1],
            ['id' => 26, 'question_id' => 7, 'answer_text' => 'Hard drive', 'is_correct' => 0],
            ['id' => 27, 'question_id' => 7, 'answer_text' => 'Monitor', 'is_correct' => 0],
            ['id' => 28, 'question_id' => 7, 'answer_text' => 'CPU', 'is_correct' => 0],
            // Q8
            ['id' => 29, 'question_id' => 8, 'answer_text' => 'Device Drivers', 'is_correct' => 1],
            ['id' => 30, 'question_id' => 8, 'answer_text' => 'Word processor', 'is_correct' => 0],
            ['id' => 31, 'question_id' => 8, 'answer_text' => 'Video editor', 'is_correct' => 0],
            ['id' => 32, 'question_id' => 8, 'answer_text' => 'Firewall', 'is_correct' => 0],
            // Q9
            ['id' => 33, 'question_id' => 9, 'answer_text' => 'Fill gaps between CPU and heatsink', 'is_correct' => 1],
            ['id' => 34, 'question_id' => 9, 'answer_text' => 'Lubricate the fan', 'is_correct' => 0],
            ['id' => 35, 'question_id' => 9, 'answer_text' => 'Glue the CPU down', 'is_correct' => 0],
            ['id' => 36, 'question_id' => 9, 'answer_text' => 'Insulate electricity', 'is_correct' => 0],
            // Q10
            ['id' => 37, 'question_id' => 10, 'answer_text' => 'systeminfo', 'is_correct' => 1],
            ['id' => 38, 'question_id' => 10, 'answer_text' => 'ipconfig', 'is_correct' => 0],
            ['id' => 39, 'question_id' => 10, 'answer_text' => 'ping', 'is_correct' => 0],
            ['id' => 40, 'question_id' => 10, 'answer_text' => 'netstat', 'is_correct' => 0],
            // Q11
            ['id' => 41, 'question_id' => 11, 'answer_text' => 'RAM', 'is_correct' => 1],
            ['id' => 42, 'question_id' => 11, 'answer_text' => 'ROM', 'is_correct' => 0],
            ['id' => 43, 'question_id' => 11, 'answer_text' => 'HDD', 'is_correct' => 0],
            ['id' => 44, 'question_id' => 11, 'answer_text' => 'Flash Drive', 'is_correct' => 0],
            // Q12
            ['id' => 45, 'question_id' => 12, 'answer_text' => 'GPT', 'is_correct' => 1],
            ['id' => 46, 'question_id' => 12, 'answer_text' => 'MBR', 'is_correct' => 0],
            ['id' => 47, 'question_id' => 12, 'answer_text' => 'FAT32', 'is_correct' => 0],
            ['id' => 48, 'question_id' => 12, 'answer_text' => 'Ext4', 'is_correct' => 0],
            // Q13
            ['id' => 49, 'question_id' => 13, 'answer_text' => 'NTFS', 'is_correct' => 1],
            ['id' => 50, 'question_id' => 13, 'answer_text' => 'HFS+', 'is_correct' => 0],
            ['id' => 51, 'question_id' => 13, 'answer_text' => 'Ext4', 'is_correct' => 0],
            ['id' => 52, 'question_id' => 13, 'answer_text' => 'FAT16', 'is_correct' => 0],
            // Q14
            ['id' => 53, 'question_id' => 14, 'answer_text' => 'HDMI', 'is_correct' => 1],
            ['id' => 54, 'question_id' => 14, 'answer_text' => 'VGA', 'is_correct' => 0],
            ['id' => 55, 'question_id' => 14, 'answer_text' => 'DVI', 'is_correct' => 0],
            ['id' => 56, 'question_id' => 14, 'answer_text' => 'PS/2', 'is_correct' => 0],
            // Q15
            ['id' => 57, 'question_id' => 15, 'answer_text' => 'Electrostatic Discharge', 'is_correct' => 1],
            ['id' => 58, 'question_id' => 15, 'answer_text' => 'Electronic System Design', 'is_correct' => 0],
            ['id' => 59, 'question_id' => 15, 'answer_text' => 'Energy Saving Device', 'is_correct' => 0],
            ['id' => 60, 'question_id' => 15, 'answer_text' => 'External Storage Disk', 'is_correct' => 0],
            // Q16
            ['id' => 61, 'question_id' => 16, 'answer_text' => 'Scanner', 'is_correct' => 1],
            ['id' => 62, 'question_id' => 16, 'answer_text' => 'Printer', 'is_correct' => 0],
            ['id' => 63, 'question_id' => 16, 'answer_text' => 'Monitor', 'is_correct' => 0],
            ['id' => 64, 'question_id' => 16, 'answer_text' => 'Speaker', 'is_correct' => 0],
            // Q17
            ['id' => 65, 'question_id' => 17, 'answer_text' => 'ATX', 'is_correct' => 1],
            ['id' => 66, 'question_id' => 17, 'answer_text' => 'ITX', 'is_correct' => 0],
            ['id' => 67, 'question_id' => 17, 'answer_text' => 'BTX', 'is_correct' => 0],
            ['id' => 68, 'question_id' => 17, 'answer_text' => 'WTX', 'is_correct' => 0],
            
            // === COC2 ===
            // Q18
            ['id' => 69, 'question_id' => 18, 'answer_text' => 'Internet Protocol', 'is_correct' => 1],
            ['id' => 70, 'question_id' => 18, 'answer_text' => 'Internal Process', 'is_correct' => 0],
            ['id' => 71, 'question_id' => 18, 'answer_text' => 'Input Port', 'is_correct' => 0],
            ['id' => 72, 'question_id' => 18, 'answer_text' => 'Intermediate Packet', 'is_correct' => 0],
            // Q19
            ['id' => 73, 'question_id' => 19, 'answer_text' => '32 bits', 'is_correct' => 1],
            ['id' => 74, 'question_id' => 19, 'answer_text' => '16 bits', 'is_correct' => 0],
            ['id' => 75, 'question_id' => 19, 'answer_text' => '64 bits', 'is_correct' => 0],
            ['id' => 76, 'question_id' => 19, 'answer_text' => '128 bits', 'is_correct' => 0],
            // Q20
            ['id' => 77, 'question_id' => 20, 'answer_text' => 'Ping', 'is_correct' => 1],
            ['id' => 78, 'question_id' => 20, 'answer_text' => 'Tracert', 'is_correct' => 0],
            ['id' => 79, 'question_id' => 20, 'answer_text' => 'Ipconfig', 'is_correct' => 0],
            ['id' => 80, 'question_id' => 20, 'answer_text' => 'Netstat', 'is_correct' => 0],
            // Q21
            ['id' => 81, 'question_id' => 21, 'answer_text' => 'Crimping tool', 'is_correct' => 1],
            ['id' => 82, 'question_id' => 21, 'answer_text' => 'Multimeter', 'is_correct' => 0],
            ['id' => 83, 'question_id' => 21, 'answer_text' => 'Screwdriver', 'is_correct' => 0],
            ['id' => 84, 'question_id' => 21, 'answer_text' => 'Punch-down tool', 'is_correct' => 0],
            // Q22
            ['id' => 85, 'question_id' => 22, 'answer_text' => 'UTP (Unshielded Twisted Pair)', 'is_correct' => 1],
            ['id' => 86, 'question_id' => 22, 'answer_text' => 'Coaxial', 'is_correct' => 0],
            ['id' => 87, 'question_id' => 22, 'answer_text' => 'Fiber optic', 'is_correct' => 0],
            ['id' => 88, 'question_id' => 22, 'answer_text' => 'USB cable', 'is_correct' => 0],
            // Q23
            ['id' => 89, 'question_id' => 23, 'answer_text' => 'Star', 'is_correct' => 1],
            ['id' => 90, 'question_id' => 23, 'answer_text' => 'Bus', 'is_correct' => 0],
            ['id' => 91, 'question_id' => 23, 'answer_text' => 'Ring', 'is_correct' => 0],
            ['id' => 92, 'question_id' => 23, 'answer_text' => 'Mesh', 'is_correct' => 0],
            // Q24
            ['id' => 93, 'question_id' => 24, 'answer_text' => 'DHCP server', 'is_correct' => 1],
            ['id' => 94, 'question_id' => 24, 'answer_text' => 'Switch', 'is_correct' => 0],
            ['id' => 95, 'question_id' => 24, 'answer_text' => 'Router', 'is_correct' => 0],
            ['id' => 96, 'question_id' => 24, 'answer_text' => 'Hub', 'is_correct' => 0],
            // Q25
            ['id' => 97, 'question_id' => 25, 'answer_text' => '255.255.255.0', 'is_correct' => 1],
            ['id' => 98, 'question_id' => 25, 'answer_text' => '255.255.0.0', 'is_correct' => 0],
            ['id' => 99, 'question_id' => 25, 'answer_text' => '255.0.0.0', 'is_correct' => 0],
            ['id' => 100, 'question_id' => 25, 'answer_text' => '255.255.255.255', 'is_correct' => 0],
            // Q26
            ['id' => 101, 'question_id' => 26, 'answer_text' => 'DNS', 'is_correct' => 1],
            ['id' => 102, 'question_id' => 26, 'answer_text' => 'HTTP', 'is_correct' => 0],
            ['id' => 103, 'question_id' => 26, 'answer_text' => 'FTP', 'is_correct' => 0],
            ['id' => 104, 'question_id' => 26, 'answer_text' => 'IP', 'is_correct' => 0],
            // Q27
            ['id' => 105, 'question_id' => 27, 'answer_text' => '127.0.0.1', 'is_correct' => 1],
            ['id' => 106, 'question_id' => 27, 'answer_text' => '192.168.1.1', 'is_correct' => 0],
            ['id' => 107, 'question_id' => 27, 'answer_text' => '10.0.0.1', 'is_correct' => 0],
            ['id' => 108, 'question_id' => 27, 'answer_text' => '255.255.255.0', 'is_correct' => 0],
            // Q28
            ['id' => 109, 'question_id' => 28, 'answer_text' => 'Media Access Control', 'is_correct' => 1],
            ['id' => 110, 'question_id' => 28, 'answer_text' => 'Multi Access Computer', 'is_correct' => 0],
            ['id' => 111, 'question_id' => 28, 'answer_text' => 'Main Area Connection', 'is_correct' => 0],
            ['id' => 112, 'question_id' => 28, 'answer_text' => 'Mass Address Control', 'is_correct' => 0],
            // Q29
            ['id' => 113, 'question_id' => 29, 'answer_text' => 'Switch', 'is_correct' => 1],
            ['id' => 114, 'question_id' => 29, 'answer_text' => 'Hub', 'is_correct' => 0],
            ['id' => 115, 'question_id' => 29, 'answer_text' => 'Repeater', 'is_correct' => 0],
            ['id' => 116, 'question_id' => 29, 'answer_text' => 'Modem', 'is_correct' => 0],
            // Q30
            ['id' => 117, 'question_id' => 30, 'answer_text' => '802.11ax', 'is_correct' => 1],
            ['id' => 118, 'question_id' => 30, 'answer_text' => '802.11ac', 'is_correct' => 0],
            ['id' => 119, 'question_id' => 30, 'answer_text' => '802.11n', 'is_correct' => 0],
            ['id' => 120, 'question_id' => 30, 'answer_text' => '802.11g', 'is_correct' => 0],
            // Q31
            ['id' => 121, 'question_id' => 31, 'answer_text' => 'White-Orange', 'is_correct' => 1],
            ['id' => 122, 'question_id' => 31, 'answer_text' => 'White-Green', 'is_correct' => 0],
            ['id' => 123, 'question_id' => 31, 'answer_text' => 'Blue', 'is_correct' => 0],
            ['id' => 124, 'question_id' => 31, 'answer_text' => 'Brown', 'is_correct' => 0],
            // Q32
            ['id' => 125, 'question_id' => 32, 'answer_text' => 'Cross-over cable', 'is_correct' => 1],
            ['id' => 126, 'question_id' => 32, 'answer_text' => 'Straight-through cable', 'is_correct' => 0],
            ['id' => 127, 'question_id' => 32, 'answer_text' => 'Rollover cable', 'is_correct' => 0],
            ['id' => 128, 'question_id' => 32, 'answer_text' => 'Coaxial cable', 'is_correct' => 0],
            // Q33
            ['id' => 129, 'question_id' => 33, 'answer_text' => 'tracert', 'is_correct' => 1],
            ['id' => 130, 'question_id' => 33, 'answer_text' => 'ping', 'is_correct' => 0],
            ['id' => 131, 'question_id' => 33, 'answer_text' => 'nslookup', 'is_correct' => 0],
            ['id' => 132, 'question_id' => 33, 'answer_text' => 'netsh', 'is_correct' => 0],
            // Q34
            ['id' => 133, 'question_id' => 34, 'answer_text' => 'Wide Area Network', 'is_correct' => 1],
            ['id' => 134, 'question_id' => 34, 'answer_text' => 'Wireless Area Network', 'is_correct' => 0],
            ['id' => 135, 'question_id' => 34, 'answer_text' => 'Web Area Network', 'is_correct' => 0],
            ['id' => 136, 'question_id' => 34, 'answer_text' => 'World Access Node', 'is_correct' => 0],

            // === COC3 ===
            // Q35
            ['id' => 137, 'question_id' => 35, 'answer_text' => 'Compressed air', 'is_correct' => 1],
            ['id' => 138, 'question_id' => 35, 'answer_text' => 'Vacuum cleaner', 'is_correct' => 0],
            ['id' => 139, 'question_id' => 35, 'answer_text' => 'Brush', 'is_correct' => 0],
            ['id' => 140, 'question_id' => 35, 'answer_text' => 'Cloth', 'is_correct' => 0],
            // Q36
            ['id' => 141, 'question_id' => 36, 'answer_text' => 'GPU', 'is_correct' => 1],
            ['id' => 142, 'question_id' => 36, 'answer_text' => 'RAM', 'is_correct' => 0],
            ['id' => 143, 'question_id' => 36, 'answer_text' => 'CPU', 'is_correct' => 0],
            ['id' => 144, 'question_id' => 36, 'answer_text' => 'PSU', 'is_correct' => 0],
            // Q37
            ['id' => 145, 'question_id' => 37, 'answer_text' => 'SFC /scannow', 'is_correct' => 1],
            ['id' => 146, 'question_id' => 37, 'answer_text' => 'Chkdsk', 'is_correct' => 0],
            ['id' => 147, 'question_id' => 37, 'answer_text' => 'Format', 'is_correct' => 0],
            ['id' => 148, 'question_id' => 37, 'answer_text' => 'Diskpart', 'is_correct' => 0],
            // Q38
            ['id' => 149, 'question_id' => 38, 'answer_text' => 'Strange noises', 'is_correct' => 1],
            ['id' => 150, 'question_id' => 38, 'answer_text' => 'Slow performance', 'is_correct' => 0],
            ['id' => 151, 'question_id' => 38, 'answer_text' => 'Frequent updates', 'is_correct' => 0],
            ['id' => 152, 'question_id' => 38, 'answer_text' => 'Blue screen', 'is_correct' => 0],
            // Q39
            ['id' => 153, 'question_id' => 39, 'answer_text' => 'Use antivirus software', 'is_correct' => 1],
            ['id' => 154, 'question_id' => 39, 'answer_text' => 'Delete system32', 'is_correct' => 0],
            ['id' => 155, 'question_id' => 39, 'answer_text' => 'Format hard drive without backup', 'is_correct' => 0],
            ['id' => 156, 'question_id' => 39, 'answer_text' => 'Ignore the malware', 'is_correct' => 0],
            // Q40
            ['id' => 157, 'question_id' => 40, 'answer_text' => 'PrtSc (Print Screen)', 'is_correct' => 1],
            ['id' => 158, 'question_id' => 40, 'answer_text' => 'Ctrl + C', 'is_correct' => 0],
            ['id' => 159, 'question_id' => 40, 'answer_text' => 'Alt + Tab', 'is_correct' => 0],
            ['id' => 160, 'question_id' => 40, 'answer_text' => 'Ctrl + Shift + Esc', 'is_correct' => 0],
            // Q41
            ['id' => 161, 'question_id' => 41, 'answer_text' => 'Converts AC to DC', 'is_correct' => 1],
            ['id' => 162, 'question_id' => 41, 'answer_text' => 'Stores data', 'is_correct' => 0],
            ['id' => 163, 'question_id' => 41, 'answer_text' => 'Controls the CPU', 'is_correct' => 0],
            ['id' => 164, 'question_id' => 41, 'answer_text' => 'Cools down the system', 'is_correct' => 0],
            // Q42
            ['id' => 165, 'question_id' => 42, 'answer_text' => 'ipconfig', 'is_correct' => 1],
            ['id' => 166, 'question_id' => 42, 'answer_text' => 'ping', 'is_correct' => 0],
            ['id' => 167, 'question_id' => 42, 'answer_text' => 'tracert', 'is_correct' => 0],
            ['id' => 168, 'question_id' => 42, 'answer_text' => 'netstat', 'is_correct' => 0],
            // Q43
            ['id' => 169, 'question_id' => 43, 'answer_text' => 'Router', 'is_correct' => 1],
            ['id' => 170, 'question_id' => 43, 'answer_text' => 'Switch', 'is_correct' => 0],
            ['id' => 171, 'question_id' => 43, 'answer_text' => 'Hub', 'is_correct' => 0],
            ['id' => 172, 'question_id' => 43, 'answer_text' => 'Firewall', 'is_correct' => 0],
            // Q44
            ['id' => 173, 'question_id' => 44, 'answer_text' => 'Use an anti-static wrist strap', 'is_correct' => 1],
            ['id' => 174, 'question_id' => 44, 'answer_text' => 'Handle components by the pins', 'is_correct' => 0],
            ['id' => 175, 'question_id' => 44, 'answer_text' => 'Place them on carpet', 'is_correct' => 0],
            ['id' => 176, 'question_id' => 44, 'answer_text' => 'Use a magnet', 'is_correct' => 0],
            // Q45
            ['id' => 177, 'question_id' => 45, 'answer_text' => 'Task Manager', 'is_correct' => 1],
            ['id' => 178, 'question_id' => 45, 'answer_text' => 'Device Manager', 'is_correct' => 0],
            ['id' => 179, 'question_id' => 45, 'answer_text' => 'Disk Management', 'is_correct' => 0],
            ['id' => 180, 'question_id' => 45, 'answer_text' => 'Event Viewer', 'is_correct' => 0],
            // Q46
            ['id' => 181, 'question_id' => 46, 'answer_text' => 'Incorrect system date/time', 'is_correct' => 1],
            ['id' => 182, 'question_id' => 46, 'answer_text' => 'System overheating', 'is_correct' => 0],
            ['id' => 183, 'question_id' => 46, 'answer_text' => 'Blue Screen of Death', 'is_correct' => 0],
            ['id' => 184, 'question_id' => 46, 'answer_text' => 'No Internet access', 'is_correct' => 0],
            // Q47
            ['id' => 185, 'question_id' => 47, 'answer_text' => 'To troubleshoot with minimal drivers', 'is_correct' => 1],
            ['id' => 186, 'question_id' => 47, 'answer_text' => 'To browse the web safely', 'is_correct' => 0],
            ['id' => 187, 'question_id' => 47, 'answer_text' => 'To encrypt data', 'is_correct' => 0],
            ['id' => 188, 'question_id' => 47, 'answer_text' => 'To speed up games', 'is_correct' => 0],
            // Q48
            ['id' => 189, 'question_id' => 48, 'answer_text' => 'Defragmentation', 'is_correct' => 1],
            ['id' => 190, 'question_id' => 48, 'answer_text' => 'Formatting', 'is_correct' => 0],
            ['id' => 191, 'question_id' => 48, 'answer_text' => 'Partitioning', 'is_correct' => 0],
            ['id' => 192, 'question_id' => 48, 'answer_text' => 'Compression', 'is_correct' => 0],
            // Q49
            ['id' => 193, 'question_id' => 49, 'answer_text' => 'Beep codes', 'is_correct' => 1],
            ['id' => 194, 'question_id' => 49, 'answer_text' => 'Slow internet', 'is_correct' => 0],
            ['id' => 195, 'question_id' => 49, 'answer_text' => 'Software error', 'is_correct' => 0],
            ['id' => 196, 'question_id' => 49, 'answer_text' => 'Flickering screen', 'is_correct' => 0],
            // Q50
            ['id' => 197, 'question_id' => 50, 'answer_text' => 'Backup existing BIOS/Data', 'is_correct' => 1],
            ['id' => 198, 'question_id' => 50, 'answer_text' => 'Format the drive', 'is_correct' => 0],
            ['id' => 199, 'question_id' => 50, 'answer_text' => 'Remove the RAM', 'is_correct' => 0],
            ['id' => 200, 'question_id' => 50, 'answer_text' => 'Install a new CPU', 'is_correct' => 0],
        ];

        $question_options = [
            // === COC1 Options ===
            // Q1
            ['question_id' => 1, 'option_text' => 'Multimeter'],
            ['question_id' => 1, 'option_text' => 'Crimping tool'],
            ['question_id' => 1, 'option_text' => 'Loopback plug'],
            ['question_id' => 1, 'option_text' => 'RJ45 tester'],
            // Q2
            ['question_id' => 2, 'option_text' => 'CPU'],
            ['question_id' => 2, 'option_text' => 'RAM'],
            ['question_id' => 2, 'option_text' => 'SSD'],
            ['question_id' => 2, 'option_text' => 'PSU'],
            // Q3
            ['question_id' => 3, 'option_text' => '7-pin data cable'],
            ['question_id' => 3, 'option_text' => '40-pin IDE ribbon'],
            ['question_id' => 3, 'option_text' => 'Ethernet cable'],
            ['question_id' => 3, 'option_text' => 'USB cable'],
            // Q4
            ['question_id' => 4, 'option_text' => 'Disconnect the power source'],
            ['question_id' => 4, 'option_text' => 'Turn off monitor only'],
            ['question_id' => 4, 'option_text' => 'Remove BIOS battery'],
            ['question_id' => 4, 'option_text' => 'Format the hard drive'],
            // Q5
            ['question_id' => 5, 'option_text' => 'PCIe x16'],
            ['question_id' => 5, 'option_text' => 'AGP'],
            ['question_id' => 5, 'option_text' => 'PCI'],
            ['question_id' => 5, 'option_text' => 'IDE'],
            // Q6
            ['question_id' => 6, 'option_text' => 'Basic Input Output System'],
            ['question_id' => 6, 'option_text' => 'Binary Input Output Setup'],
            ['question_id' => 6, 'option_text' => 'Basic Internal OS'],
            ['question_id' => 6, 'option_text' => 'Boot Input Output System'],
            // Q7
            ['question_id' => 7, 'option_text' => 'Boot device'],
            ['question_id' => 7, 'option_text' => 'Hard drive'],
            ['question_id' => 7, 'option_text' => 'Monitor'],
            ['question_id' => 7, 'option_text' => 'CPU'],
            // Q8
            ['question_id' => 8, 'option_text' => 'Device Drivers'],
            ['question_id' => 8, 'option_text' => 'Word processor'],
            ['question_id' => 8, 'option_text' => 'Video editor'],
            ['question_id' => 8, 'option_text' => 'Firewall'],
            // Q9
            ['question_id' => 9, 'option_text' => 'Fill gaps between CPU and heatsink'],
            ['question_id' => 9, 'option_text' => 'Lubricate the fan'],
            ['question_id' => 9, 'option_text' => 'Glue the CPU down'],
            ['question_id' => 9, 'option_text' => 'Insulate electricity'],
            // Q10
            ['question_id' => 10, 'option_text' => 'systeminfo'],
            ['question_id' => 10, 'option_text' => 'ipconfig'],
            ['question_id' => 10, 'option_text' => 'ping'],
            ['question_id' => 10, 'option_text' => 'netstat'],
            // Q11
            ['question_id' => 11, 'option_text' => 'RAM'],
            ['question_id' => 11, 'option_text' => 'ROM'],
            ['question_id' => 11, 'option_text' => 'HDD'],
            ['question_id' => 11, 'option_text' => 'Flash Drive'],
            // Q12
            ['question_id' => 12, 'option_text' => 'GPT'],
            ['question_id' => 12, 'option_text' => 'MBR'],
            ['question_id' => 12, 'option_text' => 'FAT32'],
            ['question_id' => 12, 'option_text' => 'Ext4'],
            // Q13
            ['question_id' => 13, 'option_text' => 'NTFS'],
            ['question_id' => 13, 'option_text' => 'HFS+'],
            ['question_id' => 13, 'option_text' => 'Ext4'],
            ['question_id' => 13, 'option_text' => 'FAT16'],
            // Q14
            ['question_id' => 14, 'option_text' => 'HDMI'],
            ['question_id' => 14, 'option_text' => 'VGA'],
            ['question_id' => 14, 'option_text' => 'DVI'],
            ['question_id' => 14, 'option_text' => 'PS/2'],
            // Q15
            ['question_id' => 15, 'option_text' => 'Electrostatic Discharge'],
            ['question_id' => 15, 'option_text' => 'Electronic System Design'],
            ['question_id' => 15, 'option_text' => 'Energy Saving Device'],
            ['question_id' => 15, 'option_text' => 'External Storage Disk'],
            // Q16
            ['question_id' => 16, 'option_text' => 'Scanner'],
            ['question_id' => 16, 'option_text' => 'Printer'],
            ['question_id' => 16, 'option_text' => 'Monitor'],
            ['question_id' => 16, 'option_text' => 'Speaker'],
            // Q17
            ['question_id' => 17, 'option_text' => 'ATX'],
            ['question_id' => 17, 'option_text' => 'ITX'],
            ['question_id' => 17, 'option_text' => 'BTX'],
            ['question_id' => 17, 'option_text' => 'WTX'],

            // === COC2 Options ===
            // Q18
            ['question_id' => 18, 'option_text' => 'Internet Protocol'],
            ['question_id' => 18, 'option_text' => 'Internal Process'],
            ['question_id' => 18, 'option_text' => 'Input Port'],
            ['question_id' => 18, 'option_text' => 'Intermediate Packet'],
            // Q19
            ['question_id' => 19, 'option_text' => '32 bits'],
            ['question_id' => 19, 'option_text' => '16 bits'],
            ['question_id' => 19, 'option_text' => '64 bits'],
            ['question_id' => 19, 'option_text' => '128 bits'],
            // Q20
            ['question_id' => 20, 'option_text' => 'Ping'],
            ['question_id' => 20, 'option_text' => 'Tracert'],
            ['question_id' => 20, 'option_text' => 'Ipconfig'],
            ['question_id' => 20, 'option_text' => 'Netstat'],
            // Q21
            ['question_id' => 21, 'option_text' => 'Crimping tool'],
            ['question_id' => 21, 'option_text' => 'Multimeter'],
            ['question_id' => 21, 'option_text' => 'Screwdriver'],
            ['question_id' => 21, 'option_text' => 'Punch-down tool'],
            // Q22
            ['question_id' => 22, 'option_text' => 'UTP (Unshielded Twisted Pair)'],
            ['question_id' => 22, 'option_text' => 'Coaxial'],
            ['question_id' => 22, 'option_text' => 'Fiber optic'],
            ['question_id' => 22, 'option_text' => 'USB cable'],
            // Q23
            ['question_id' => 23, 'option_text' => 'Star'],
            ['question_id' => 23, 'option_text' => 'Bus'],
            ['question_id' => 23, 'option_text' => 'Ring'],
            ['question_id' => 23, 'option_text' => 'Mesh'],
            // Q24
            ['question_id' => 24, 'option_text' => 'DHCP server'],
            ['question_id' => 24, 'option_text' => 'Switch'],
            ['question_id' => 24, 'option_text' => 'Router'],
            ['question_id' => 24, 'option_text' => 'Hub'],
            // Q25
            ['question_id' => 25, 'option_text' => '255.255.255.0'],
            ['question_id' => 25, 'option_text' => '255.255.0.0'],
            ['question_id' => 25, 'option_text' => '255.0.0.0'],
            ['question_id' => 25, 'option_text' => '255.255.255.255'],
            // Q26
            ['question_id' => 26, 'option_text' => 'DNS'],
            ['question_id' => 26, 'option_text' => 'HTTP'],
            ['question_id' => 26, 'option_text' => 'FTP'],
            ['question_id' => 26, 'option_text' => 'IP'],
            // Q27
            ['question_id' => 27, 'option_text' => '127.0.0.1'],
            ['question_id' => 27, 'option_text' => '192.168.1.1'],
            ['question_id' => 27, 'option_text' => '10.0.0.1'],
            ['question_id' => 27, 'option_text' => '255.255.255.0'],
            // Q28
            ['question_id' => 28, 'option_text' => 'Media Access Control'],
            ['question_id' => 28, 'option_text' => 'Multi Access Computer'],
            ['question_id' => 28, 'option_text' => 'Main Area Connection'],
            ['question_id' => 28, 'option_text' => 'Mass Address Control'],
            // Q29
            ['question_id' => 29, 'option_text' => 'Switch'],
            ['question_id' => 29, 'option_text' => 'Hub'],
            ['question_id' => 29, 'option_text' => 'Repeater'],
            ['question_id' => 29, 'option_text' => 'Modem'],
            // Q30
            ['question_id' => 30, 'option_text' => '802.11ax'],
            ['question_id' => 30, 'option_text' => '802.11ac'],
            ['question_id' => 30, 'option_text' => '802.11n'],
            ['question_id' => 30, 'option_text' => '802.11g'],
            // Q31
            ['question_id' => 31, 'option_text' => 'White-Orange'],
            ['question_id' => 31, 'option_text' => 'White-Green'],
            ['question_id' => 31, 'option_text' => 'Blue'],
            ['question_id' => 31, 'option_text' => 'Brown'],
            // Q32
            ['question_id' => 32, 'option_text' => 'Cross-over cable'],
            ['question_id' => 32, 'option_text' => 'Straight-through cable'],
            ['question_id' => 32, 'option_text' => 'Rollover cable'],
            ['question_id' => 32, 'option_text' => 'Coaxial cable'],
            // Q33
            ['question_id' => 33, 'option_text' => 'tracert'],
            ['question_id' => 33, 'option_text' => 'ping'],
            ['question_id' => 33, 'option_text' => 'nslookup'],
            ['question_id' => 33, 'option_text' => 'netsh'],
            // Q34
            ['question_id' => 34, 'option_text' => 'Wide Area Network'],
            ['question_id' => 34, 'option_text' => 'Wireless Area Network'],
            ['question_id' => 34, 'option_text' => 'Web Area Network'],
            ['question_id' => 34, 'option_text' => 'World Access Node'],

            // === COC3 Options ===
            // Q35
            ['question_id' => 35, 'option_text' => 'Compressed air'],
            ['question_id' => 35, 'option_text' => 'Vacuum cleaner'],
            ['question_id' => 35, 'option_text' => 'Brush'],
            ['question_id' => 35, 'option_text' => 'Cloth'],
            // Q36
            ['question_id' => 36, 'option_text' => 'GPU'],
            ['question_id' => 36, 'option_text' => 'RAM'],
            ['question_id' => 36, 'option_text' => 'CPU'],
            ['question_id' => 36, 'option_text' => 'PSU'],
            // Q37
            ['question_id' => 37, 'option_text' => 'SFC /scannow'],
            ['question_id' => 37, 'option_text' => 'Chkdsk'],
            ['question_id' => 37, 'option_text' => 'Format'],
            ['question_id' => 37, 'option_text' => 'Diskpart'],
            // Q38
            ['question_id' => 38, 'option_text' => 'Strange noises'],
            ['question_id' => 38, 'option_text' => 'Slow performance'],
            ['question_id' => 38, 'option_text' => 'Frequent updates'],
            ['question_id' => 38, 'option_text' => 'Blue screen'],
            // Q39
            ['question_id' => 39, 'option_text' => 'Use antivirus software'],
            ['question_id' => 39, 'option_text' => 'Delete system32'],
            ['question_id' => 39, 'option_text' => 'Format hard drive without backup'],
            ['question_id' => 39, 'option_text' => 'Ignore the malware'],
            // Q40
            ['question_id' => 40, 'option_text' => 'PrtSc (Print Screen)'],
            ['question_id' => 40, 'option_text' => 'Ctrl + C'],
            ['question_id' => 40, 'option_text' => 'Alt + Tab'],
            ['question_id' => 40, 'option_text' => 'Ctrl + Shift + Esc'],
            // Q41
            ['question_id' => 41, 'option_text' => 'Converts AC to DC'],
            ['question_id' => 41, 'option_text' => 'Stores data'],
            ['question_id' => 41, 'option_text' => 'Controls the CPU'],
            ['question_id' => 41, 'option_text' => 'Cools down the system'],
            // Q42
            ['question_id' => 42, 'option_text' => 'ipconfig'],
            ['question_id' => 42, 'option_text' => 'ping'],
            ['question_id' => 42, 'option_text' => 'tracert'],
            ['question_id' => 42, 'option_text' => 'netstat'],
            // Q43
            ['question_id' => 43, 'option_text' => 'Router'],
            ['question_id' => 43, 'option_text' => 'Switch'],
            ['question_id' => 43, 'option_text' => 'Hub'],
            ['question_id' => 43, 'option_text' => 'Firewall'],
            // Q44
            ['question_id' => 44, 'option_text' => 'Use an anti-static wrist strap'],
            ['question_id' => 44, 'option_text' => 'Handle components by the pins'],
            ['question_id' => 44, 'option_text' => 'Place them on carpet'],
            ['question_id' => 44, 'option_text' => 'Use a magnet'],
            // Q45
            ['question_id' => 45, 'option_text' => 'Task Manager'],
            ['question_id' => 45, 'option_text' => 'Device Manager'],
            ['question_id' => 45, 'option_text' => 'Disk Management'],
            ['question_id' => 45, 'option_text' => 'Event Viewer'],
            // Q46
            ['question_id' => 46, 'option_text' => 'Incorrect system date/time'],
            ['question_id' => 46, 'option_text' => 'System overheating'],
            ['question_id' => 46, 'option_text' => 'Blue Screen of Death'],
            ['question_id' => 46, 'option_text' => 'No Internet access'],
            // Q47
            ['question_id' => 47, 'option_text' => 'To troubleshoot with minimal drivers'],
            ['question_id' => 47, 'option_text' => 'To browse the web safely'],
            ['question_id' => 47, 'option_text' => 'To encrypt data'],
            ['question_id' => 47, 'option_text' => 'To speed up games'],
            // Q48
            ['question_id' => 48, 'option_text' => 'Defragmentation'],
            ['question_id' => 48, 'option_text' => 'Formatting'],
            ['question_id' => 48, 'option_text' => 'Partitioning'],
            ['question_id' => 48, 'option_text' => 'Compression'],
            // Q49
            ['question_id' => 49, 'option_text' => 'Beep codes'],
            ['question_id' => 49, 'option_text' => 'Slow internet'],
            ['question_id' => 49, 'option_text' => 'Software error'],
            ['question_id' => 49, 'option_text' => 'Flickering screen'],
            // Q50
            ['question_id' => 50, 'option_text' => 'Backup existing BIOS/Data'],
            ['question_id' => 50, 'option_text' => 'Format the drive'],
            ['question_id' => 50, 'option_text' => 'Remove the RAM'],
            ['question_id' => 50, 'option_text' => 'Install a new CPU'],
        ];

        DB::table('question_options')->insert($question_options);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('answers')->insert($answers);
        DB::table('quizzes')->insert($quizzes);
    }
}