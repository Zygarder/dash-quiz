INSERT INTO quizzes (title, description, created_at, updated_at) VALUES	
-- Quiz 1
('Hardware Fundamentals and Tools', 'Computer hardware components, essential tools, safety procedures, and basic assembly skills.',NOW(),NOW());
-- Quiz 2
('Software Fundamentals and Troubleshooting',
'Computer software, BIOS settings, operating system basics, file systems, utilities, and basic troubleshooting procedures essential', NOW(), NOW());
-- Quiz 3
('Computer Maintenance and Troubleshooting',
'Assess your skills in preventive maintenance, diagnostics, troubleshooting common hardware and software issues, cleaning procedures, and performance optimization', NOW(), NOW());


-- QUESTIONS for Quiz 1
INSERT INTO questions (quiz_id, question_text, created_at, updated_at) VALUES
(1,'Which tool is used to tighten screws inside a computer?',NOW(),NOW()),
(1,'What is the purpose of an anti‑static wrist strap?',NOW(),NOW()),
(1,'Which component stores data long‑term?',NOW(),NOW()),
(1,'Which part interprets and executes instructions?',NOW(),NOW()),
(1,'What does the motherboard hold?',NOW(),NOW()),
(1,'Which device displays visual output?',NOW(),NOW()),
(1,'Which tool detects voltage and resistance?',NOW(),NOW()),
(1,'What does RAM stand for?',NOW(),NOW()),
(1,'Which part provides power to a computer?',NOW(),NOW()),
(1,'Which component stores BIOS settings?',NOW(),NOW()),
(1,'Which device reads and writes CDs/DVDs?',NOW(),NOW()),
(1,'What helps reduce static when servicing hardware?',NOW(),NOW()),
(1,'Which cable connects a computer to a LAN router?',NOW(),NOW()),
(1,'What component expands memory capacity?',NOW(),NOW()),
(1,'Which part determines CPU operating speed?',NOW(),NOW()),
(1,'Which hardware connector is for monitors?',NOW(),NOW()),
(1,'Which device outputs sound?',NOW(),NOW()),
(1,'What does PSU stand for?',NOW(),NOW()),
(1,'Which tool tests network cables?',NOW(),NOW()),
(1,'Which component holds firmware before OS loads?',NOW(),NOW()),
(1,'Which part stores temporary operational data?',NOW(),NOW()),
(1,'Which device transmits data over long distances?',NOW(),NOW()),
(1,'Which part manages USB connections?',NOW(),NOW()),
(1,'Which connector is used for Ethernet networks?',NOW(),NOW()),
(1,'Which tool loosens screws safely without damage?',NOW(),NOW());

-- QUESTIONS for Quiz 2
(2,'Which file system is commonly used in Windows installations?',NOW(),NOW()),
(2,'What is the main purpose of BIOS?',NOW(),NOW()),
(2,'Which utility checks and repairs missing or corrupt system files?',NOW(),NOW()),
(2,'Which command checks disk integrity and fixes errors?',NOW(),NOW()),
(2,'What does the Windows Registry store?',NOW(),NOW()),
(2,'Which partition style supports drives larger than 2TB?',NOW(),NOW()),
(2,'Which tool displays system configuration and OS info?',NOW(),NOW()),
(2,'What is the default Windows shell?',NOW(),NOW()),
(2,'Which software updates improve system stability and security?',NOW(),NOW()),
(2,'What is preventive maintenance for software?',NOW(),NOW()),
(2,'Which process clears temporary files to improve performance?',NOW(),NOW()),
(2,'Which command displays network configuration?',NOW(),NOW()),
(2,'Which utility helps manage disk partitions?',NOW(),NOW()),
(2,'Which tool restores system to an earlier state?',NOW(),NOW()),
(2,'What is the purpose of device drivers?',NOW(),NOW()),
(2,'Which software helps detect malware or viruses?',NOW(),NOW()),
(2,'Which OS feature manages startup applications?',NOW(),NOW()),
(2,'Which Windows feature backs up files automatically?',NOW(),NOW()),
(2,'What is the function of a system restore point?',NOW(),NOW()),
(2,'Which process defragments fragmented files?',NOW(),NOW()),
(2,'Which command lists active processes?',NOW(),NOW()),
(2,'Which setting controls user permissions?',NOW(),NOW()),
(2,'Which software installation type affects all users?',NOW(),NOW()),
(2,'What is a common sign of software malfunction?',NOW(),NOW()),
(2,'Which utility repairs boot-related problems?',NOW(),NOW());


-- QUESTIONS for Quiz 3
INSERT INTO questions (quiz_id, question_text, created_at, updated_at) VALUES
(3,'What is the main purpose of preventive maintenance?',NOW(),NOW()),
(3,'Which tool is used to remove dust from computer components safely?',NOW(),NOW()),
(3,'Which action extends the life of a hard disk?',NOW(),NOW()),
(3,'What should be done before cleaning internal components?',NOW(),NOW()),
(3,'Which utility checks for software errors in Windows?',NOW(),NOW()),
(3,'Which hardware maintenance task improves airflow?',NOW(),NOW()),
(3,'What is the function of thermal paste?',NOW(),NOW()),
(3,'Which sign indicates a failing power supply?',NOW(),NOW()),
(3,'Which method prevents electrostatic discharge when servicing a PC?',NOW(),NOW()),
(3,'Which type of backup protects against accidental deletion?',NOW(),NOW()),
(3,'Which software helps detect fragmented files?',NOW(),NOW()),
(3,'Which OS tool monitors CPU and memory usage?',NOW(),NOW()),
(3,'Which component should be checked if the system overheats?',NOW(),NOW()),
(3,'What is the purpose of defragmenting a hard drive?',NOW(),NOW()),
(3,'Which symptom may indicate malware infection?',NOW(),NOW()),
(3,'Which action improves Windows boot time?',NOW(),NOW()),
(3,'Which is the first step in troubleshooting hardware issues?',NOW(),NOW()),
(3,'Which component requires regular firmware updates?',NOW(),NOW()),
(3,'What is the recommended frequency for checking system logs?',NOW(),NOW()),
(3,'Which action can prevent data loss during power failures?',NOW(),NOW()),
(3,'Which cleaning tool can remove dust from keyboard and fans?',NOW(),NOW()),
(3,'Which preventive measure improves system performance?',NOW(),NOW()),
(3,'Which device requires periodic battery maintenance?',NOW(),NOW()),
(3,'Which symptom indicates a corrupted OS installation?',NOW(),NOW()),
(3,'Which maintenance improves overall network performance?',NOW(),NOW());

-- QUESTION OPTIONS for Quiz 1
-- Q1
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(1,'Screwdriver',1,NOW(),NOW()),
(1,'Hammer',0,NOW(),NOW()),
(1,'Paint brush',0,NOW(),NOW()),
(1,'Flashlight',0,NOW(),NOW());

-- Q2
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(2,'Prevent static discharge',1,NOW(),NOW()),
(2,'Increase brightness',0,NOW(),NOW()),
(2,'Cool the CPU',0,NOW(),NOW()),
(2,'Store data',0,NOW(),NOW());

-- Q3
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(3,'Hard drive',1,NOW(),NOW()),
(3,'RAM only',0,NOW(),NOW()),
(3,'CPU only',0,NOW(),NOW()),
(3,'Monitor',0,NOW(),NOW());

-- Q4
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(4,'CPU',1,NOW(),NOW()),
(4,'Keyboard',0,NOW(),NOW()),
(4,'Speaker',0,NOW(),NOW()),
(4,'Mouse',0,NOW(),NOW());

-- Q5
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(5,'Motherboard',1,NOW(),NOW()),
(5,'Printer',0,NOW(),NOW()),
(5,'Router',0,NOW(),NOW()),
(5,'Cable',0,NOW(),NOW());

-- Q6
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(6,'Monitor',1,NOW(),NOW()),
(6,'Network card',0,NOW(),NOW()),
(6,'Battery',0,NOW(),NOW()),
(6,'Printer port',0,NOW(),NOW());

-- Q7
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(7,'Multimeter',1,NOW(),NOW()),
(7,'Screwdriver handle',0,NOW(),NOW()),
(7,'Keyboard',0,NOW(),NOW()),
(7,'Flash drive',0,NOW(),NOW());

-- Q8
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(8,'Random Access Memory',1,NOW(),NOW()),
(8,'Read After Memory',0,NOW(),NOW()),
(8,'Rapid Access Module',0,NOW(),NOW()),
(8,'Ready and Active Memory',0,NOW(),NOW());

-- Q9
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(9,'Power supply unit',1,NOW(),NOW()),
(9,'Hub',0,NOW(),NOW()),
(9,'Mouse',0,NOW(),NOW()),
(9,'Printer',0,NOW(),NOW());

-- Q10
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(10,'CMOS battery',1,NOW(),NOW()),
(10,'CPU fan',0,NOW(),NOW()),
(10,'USB stick',0,NOW(),NOW()),
(10,'Network cable',0,NOW(),NOW());

-- Q11
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(11,'Optical drive',1,NOW(),NOW()),
(11,'Screwdriver',0,NOW(),NOW()),
(11,'Monitor',0,NOW(),NOW()),
(11,'Network tester',0,NOW(),NOW());

-- Q12
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(12,'Anti‑static wrist strap',1,NOW(),NOW()),
(12,'Rubber glove',0,NOW(),NOW()),
(12,'Blanket',0,NOW(),NOW()),
(12,'Phone',0,NOW(),NOW());

-- Q13
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(13,'Ethernet cable',1,NOW(),NOW()),
(13,'VGA cable',0,NOW(),NOW()),
(13,'PS/2 cable',0,NOW(),NOW()),
(13,'Parallel cable',0,NOW(),NOW());

-- Q14
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(14,'RAM module',1,NOW(),NOW()),
(14,'Keyboard cable',0,NOW(),NOW()),
(14,'Monitor port',0,NOW(),NOW()),
(14,'USB stick',0,NOW(),NOW());

-- Q15
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(15,'Clock generator',1,NOW(),NOW()),
(15,'Mouse',0,NOW(),NOW()),
(15,'Printer',0,NOW(),NOW()),
(15,'Speaker',0,NOW(),NOW());

-- Q16
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(16,'VGA',1,NOW(),NOW()),
(16,'USB',0,NOW(),NOW()),
(16,'HDMI',0,NOW(),NOW()),
(16,'RJ45',0,NOW(),NOW());

-- Q17
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(17,'Speakers',1,NOW(),NOW()),
(17,'CPU core',0,NOW(),NOW()),
(17,'CMOS',0,NOW(),NOW()),
(17,'PSU only',0,NOW(),NOW());

-- Q18
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(18,'Power Supply Unit',1,NOW(),NOW()),
(18,'Printer only',0,NOW(),NOW()),
(18,'Router only',0,NOW(),NOW()),
(18,'Network card',0,NOW(),NOW());

-- Q19
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(19,'Cable tester',1,NOW(),NOW()),
(19,'Paintbrush',0,NOW(),NOW()),
(19,'Multitool',0,NOW(),NOW()),
(19,'Webcam',0,NOW(),NOW());

-- Q20
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(20,'BIOS/firmware',1,NOW(),NOW()),
(20,'Keyboard only',0,NOW(),NOW()),
(20,'Monitor',0,NOW(),NOW()),
(20,'Printer port',0,NOW(),NOW());

-- Q21
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(21,'Temporary storage (RAM)',1,NOW(),NOW()),
(21,'Hard Drive only',0,NOW(),NOW()),
(21,'USB stick',0,NOW(),NOW()),
(21,'Printer buffer',0,NOW(),NOW());

-- Q22
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(22,'Modem',1,NOW(),NOW()),
(22,'USB cable',0,NOW(),NOW()),
(22,'SATA cable',0,NOW(),NOW()),
(22,'VGA monitor',0,NOW(),NOW());

-- Q23
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(23,'Universal Serial Bus',1,NOW(),NOW()),
(23,'Video Serial Unit',0,NOW(),NOW()),
(23,'Virtual Storage Bus',0,NOW(),NOW()),
(23,'Visual Service Box',0,NOW(),NOW());

-- Q24
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(24,'RJ45',1,NOW(),NOW()),
(24,'USB',0,NOW(),NOW()),
(24,'HDMI',0,NOW(),NOW()),
(24,'VGA',0,NOW(),NOW());

-- Q25
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(25,'Nut driver',1,NOW(),NOW()),
(25,'Flash drive',0,NOW(),NOW()),
(25,'Stylus pen',0,NOW(),NOW()),
(25,'Monitor stand',0,NOW(),NOW());


-- OPTIONS for Quiz 2
-- Q1
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(26,'NTFS',1,NOW(),NOW()),
(26,'FAT16',0,NOW(),NOW()),
(26,'EXT4',0,NOW(),NOW()),
(26,'APFS',0,NOW(),NOW());

-- Q2
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(27,'Basic Input/Output System',1,NOW(),NOW()),
(27,'Random Access Memory',0,NOW(),NOW()),
(27,'Device driver',0,NOW(),NOW()),
(27,'Operating System',0,NOW(),NOW());

-- Q3
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(28,'sfc',1,NOW(),NOW()),
(28,'chkdsk',0,NOW(),NOW()),
(28,'defrag',0,NOW(),NOW()),
(28,'ipconfig',0,NOW(),NOW());

-- Q4
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(29,'chkdsk',1,NOW(),NOW()),
(29,'sfc',0,NOW(),NOW()),
(29,'format',0,NOW(),NOW()),
(29,'diskpart',0,NOW(),NOW());

-- Q5
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(30,'System and application settings',1,NOW(),NOW()),
(30,'Only antivirus logs',0,NOW(),NOW()),
(30,'Just fonts',0,NOW(),NOW()),
(30,'Printer ports',0,NOW(),NOW());

-- Q6
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(31,'GPT',1,NOW(),NOW()),
(31,'MBR',0,NOW(),NOW()),
(31,'FAT32',0,NOW(),NOW()),
(31,'HPFS',0,NOW(),NOW());

-- Q7
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(32,'System Information (msinfo32)',1,NOW(),NOW()),
(32,'Disk Management',0,NOW(),NOW()),
(32,'Task Scheduler',0,NOW(),NOW()),
(32,'Device Manager',0,NOW(),NOW());

-- Q8
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(33,'Command Prompt',1,NOW(),NOW()),
(33,'PowerShell',0,NOW(),NOW()),
(33,'Registry Editor',0,NOW(),NOW()),
(33,'Notepad',0,NOW(),NOW());

-- Q9
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(34,'Updates',1,NOW(),NOW()),
(34,'Themes',0,NOW(),NOW()),
(34,'Wallpaper',0,NOW(),NOW()),
(34,'Screensaver',0,NOW(),NOW());

-- Q10
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(35,'Regular updates and patches',1,NOW(),NOW()),
(35,'Ignore errors',0,NOW(),NOW()),
(35,'Install random software',0,NOW(),NOW()),
(35,'Restart repeatedly',0,NOW(),NOW());

-- Q11
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(36,'Disk cleanup',1,NOW(),NOW()),
(36,'Disk defrag',0,NOW(),NOW()),
(36,'Task Manager',0,NOW(),NOW()),
(36,'Control Panel',0,NOW(),NOW());

-- Q12
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(37,'ipconfig',1,NOW(),NOW()),
(37,'ping',0,NOW(),NOW()),
(37,'tracert',0,NOW(),NOW()),
(37,'netstat',0,NOW(),NOW());

-- Q13
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(38,'Disk Management',1,NOW(),NOW()),
(38,'Task Scheduler',0,NOW(),NOW()),
(38,'Event Viewer',0,NOW(),NOW()),
(38,'MS Paint',0,NOW(),NOW());

-- Q14
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(39,'System Restore',1,NOW(),NOW()),
(39,'Device Manager',0,NOW(),NOW()),
(39,'Defrag',0,NOW(),NOW()),
(39,'Firewall',0,NOW(),NOW());

-- Q15
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(40,'To allow OS to communicate with hardware',1,NOW(),NOW()),
(40,'To print files',0,NOW(),NOW()),
(40,'To install fonts',0,NOW(),NOW()),
(40,'To create shortcuts',0,NOW(),NOW());

-- Q16
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(41,'Antivirus software',1,NOW(),NOW()),
(41,'Paint',0,NOW(),NOW()),
(41,'Notepad',0,NOW(),NOW()),
(41,'Disk Cleanup',0,NOW(),NOW());

-- Q17
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(42,'Task Manager',1,NOW(),NOW()),
(42,'Disk Management',0,NOW(),NOW()),
(42,'Registry Editor',0,NOW(),NOW()),
(42,'Command Prompt',0,NOW(),NOW());

-- Q18
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(43,'File History',1,NOW(),NOW()),
(43,'Event Viewer',0,NOW(),NOW()),
(43,'Disk Cleanup',0,NOW(),NOW()),
(43,'Windows Media Player',0,NOW(),NOW());

-- Q19
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(44,'Restore previous system configuration',1,NOW(),NOW()),
(44,'Uninstall programs',0,NOW(),NOW()),
(44,'Edit wallpaper',0,NOW(),NOW()),
(44,'Clear clipboard',0,NOW(),NOW());

-- Q20
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(45,'Defragmentation',1,NOW(),NOW()),
(45,'Disk cleanup',0,NOW(),NOW()),
(45,'Format',0,NOW(),NOW()),
(45,'Registry backup',0,NOW(),NOW());

-- Q21
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(46,'Tasklist',1,NOW(),NOW()),
(46,'ipconfig',0,NOW(),NOW()),
(46,'ping',0,NOW(),NOW()),
(46,'tracert',0,NOW(),NOW());

-- Q22
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(47,'User Account Control',1,NOW(),NOW()),
(47,'Firewall',0,NOW(),NOW()),
(47,'Windows Media Player',0,NOW(),NOW()),
(47,'Notepad',0,NOW(),NOW());

-- Q23
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(48,'For all users',1,NOW(),NOW()),
(48,'Only for administrator',0,NOW(),NOW()),
(48,'Only for system',0,NOW(),NOW()),
(48,'Only for guest',0,NOW(),NOW());

-- Q24
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(49,'System crashes or error messages',1,NOW(),NOW()),
(49,'Faster boot time',0,NOW(),NOW()),
(49,'Clear screen',0,NOW(),NOW()),
(49,'Wallpaper change',0,NOW(),NOW());

-- Q25
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(50,'Startup Repair',1,NOW(),NOW()),
(50,'Notepad',0,NOW(),NOW()),
(50,'Disk Cleanup',0,NOW(),NOW()),
(50,'Device Manager',0,NOW(),NOW());


-- OPTIONS for Quiz 3
-- Q1
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(51,'To prevent hardware and software problems before they occur',1,NOW(),NOW()),
(51,'To repair broken components after failure',0,NOW(),NOW()),
(51,'To install new software updates',0,NOW(),NOW()),
(51,'To increase internet speed',0,NOW(),NOW());

-- Q2
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(52,'Compressed air canister',1,NOW(),NOW()),
(52,'Wet cloth',0,NOW(),NOW()),
(52,'Vacuum cleaner',0,NOW(),NOW()),
(52,'Sandpaper',0,NOW(),NOW());

-- Q3
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(53,'Defragmenting and proper shutdowns',1,NOW(),NOW()),
(53,'Leaving it on all the time',0,NOW(),NOW()),
(53,'Using overclocking tools',0,NOW(),NOW()),
(53,'Frequent reinstall of OS',0,NOW(),NOW());

-- Q4
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(54,'Turn off the power and unplug the system',1,NOW(),NOW()),
(54,'Keep the system running',0,NOW(),NOW()),
(54,'Remove components while on',0,NOW(),NOW()),
(54,'Spray water to clean',0,NOW(),NOW());

-- Q5
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(55,'sfc /scannow',1,NOW(),NOW()),
(55,'chkdsk /f',0,NOW(),NOW()),
(55,'defrag',0,NOW(),NOW()),
(55,'ipconfig',0,NOW(),NOW());

-- Q6
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(56,'Cleaning dust from fans and vents',1,NOW(),NOW()),
(56,'Installing more RAM',0,NOW(),NOW()),
(56,'Overclocking CPU',0,NOW(),NOW()),
(56,'Updating the BIOS',0,NOW(),NOW());

-- Q7
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(57,'To improve heat transfer between CPU and heatsink',1,NOW(),NOW()),
(57,'To store thermal energy',0,NOW(),NOW()),
(57,'To increase fan speed',0,NOW(),NOW()),
(57,'To clean the motherboard',0,NOW(),NOW());

-- Q8
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(58,'Frequent system shutdowns or power loss',1,NOW(),NOW()),
(58,'Slow mouse movement',0,NOW(),NOW()),
(58,'Wallpaper changes',0,NOW(),NOW()),
(58,'Empty recycle bin',0,NOW(),NOW());

-- Q9
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(59,'Use an anti-static wrist strap',1,NOW(),NOW()),
(59,'Wear rubber shoes only',0,NOW(),NOW()),
(59,'Use wet cloth',0,NOW(),NOW()),
(59,'Touch power supply pins',0,NOW(),NOW());

-- Q10
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(60,'Regular backups',1,NOW(),NOW()),
(60,'Only shutdowns',0,NOW(),NOW()),
(60,'Install games',0,NOW(),NOW()),
(60,'Increase brightness',0,NOW(),NOW());

-- Q11
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(61,'Disk Cleanup or defragmentation software',1,NOW(),NOW()),
(61,'Notepad',0,NOW(),NOW()),
(61,'MS Paint',0,NOW(),NOW()),
(61,'Task Scheduler',0,NOW(),NOW());

-- Q12
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(62,'Task Manager',1,NOW(),NOW()),
(62,'Control Panel',0,NOW(),NOW()),
(62,'Registry Editor',0,NOW(),NOW()),
(62,'Disk Management',0,NOW(),NOW());

-- Q13
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(63,'CPU and heatsinks',1,NOW(),NOW()),
(63,'Hard disk only',0,NOW(),NOW()),
(63,'Monitor',0,NOW(),NOW()),
(63,'Keyboard',0,NOW(),NOW());

-- Q14
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(64,'To organize files for faster access',1,NOW(),NOW()),
(64,'To delete files',0,NOW(),NOW()),
(64,'To backup files',0,NOW(),NOW()),
(64,'To format disk',0,NOW(),NOW());

-- Q15
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(65,'Frequent pop-ups, slow performance, unknown programs',1,NOW(),NOW()),
(65,'Wallpaper change',0,NOW(),NOW()),
(65,'Faster boot time',0,NOW(),NOW()),
(65,'Clear cache',0,NOW(),NOW());

-- Q16
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(66,'Disable unnecessary startup programs',1,NOW(),NOW()),
(66,'Install games',0,NOW(),NOW()),
(66,'Change wallpaper',0,NOW(),NOW()),
(66,'Remove keyboard',0,NOW(),NOW());

-- Q17
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(67,'Check connections and test hardware components',1,NOW(),NOW()),
(67,'Reinstall OS immediately',0,NOW(),NOW()),
(67,'Open BIOS without need',0,NOW(),NOW()),
(67,'Update drivers randomly',0,NOW(),NOW());

-- Q18
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(68,'Motherboard',1,NOW(),NOW()),
(68,'Keyboard',0,NOW(),NOW()),
(68,'Monitor',0,NOW(),NOW()),
(68,'Mouse',0,NOW(),NOW());

-- Q19
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(69,'Once a week or monthly depending on usage',1,NOW(),NOW()),
(69,'Never',0,NOW(),NOW()),
(69,'Only when errors occur',0,NOW(),NOW()),
(69,'Daily',0,NOW(),NOW());

-- Q20
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(70,'Use of Uninterruptible Power Supply (UPS)',1,NOW(),NOW()),
(70,'Only turn off monitor',0,NOW(),NOW()),
(70,'Install software randomly',0,NOW(),NOW()),
(70,'Clean only screen',0,NOW(),NOW());

-- Q21
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(71,'Compressed air and small brush',1,NOW(),NOW()),
(71,'Sandpaper',0,NOW(),NOW()),
(71,'Water spray',0,NOW(),NOW()),
(71,'Vacuum without filter',0,NOW(),NOW());

-- Q22
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(72,'Regular software updates and disk cleanup',1,NOW(),NOW()),
(72,'Leave PC idle',0,NOW(),NOW()),
(72,'Change wallpaper',0,NOW(),NOW()),
(72,'Only power off',0,NOW(),NOW());

-- Q23
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(73,'Laptop battery',1,NOW(),NOW()),
(73,'Monitor',0,NOW(),NOW()),
(73,'Keyboard',0,NOW(),NOW()),
(73,'Mouse',0,NOW(),NOW());

-- Q24
INSERT INTO question_options (question_id, option_text, is_correct, created_at, updated_at) VALUES
(74,'Frequent system crashes or boot errors',1,NOW(),NOW()),
(74,'Wallpaper changes',0,NOW(),NOW()),
(74,'Faster mouse',0,NOW(),NOW()),
(74,'File rename',0,NOW(),NOW());