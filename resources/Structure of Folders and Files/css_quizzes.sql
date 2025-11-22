INSERT INTO quizzes (id, title, description) VALUES
(1, 'COC1 CSS Quiz', 'Basic networking and IT fundamentals quiz for COC1.'),
(2, 'COC2 CSS Quiz', 'Intermediate computer systems and hardware knowledge quiz for COC2.'),
(3, 'COC3 CSS Quiz', 'Advanced networking, OS, and cybersecurity quiz for COC3.');

-- Quiz 1: COC1
INSERT INTO questions (id, quiz_id, question_text, correct_option_id) VALUES
(1, 1, 'Which cable type is most commonly used in LANs?', NULL),
(2, 1, 'What does IP stand for in networking?', NULL),
(3, 1, 'Which device directs network traffic?', NULL),
(4, 1, 'Which protocol is used to send emails?', NULL),
(5, 1, 'What is the main function of DNS?', NULL),
(6, 1, 'Which topology has a central hub?', NULL),
(7, 1, 'Which layer is responsible for IP addressing?', NULL),
(8, 1, 'What is the default port for HTTP?', NULL),
(9, 1, 'Which device connects multiple networks?', NULL),
(10, 1, 'Which OSI layer handles encryption?', NULL);

-- Quiz 2: COC2
INSERT INTO questions (id, quiz_id, question_text, correct_option_id) VALUES
(11, 2, 'Which command shows the IP configuration in Windows?', NULL),
(12, 2, 'What is the default gateway used for?', NULL),
(13, 2, 'Which type of memory is volatile?', NULL),
(14, 2, 'Which software is required after installing Windows?', NULL),
(15, 2, 'Which component stores BIOS settings?', NULL),
(16, 2, 'Which CPU component executes instructions?', NULL),
(17, 2, 'Which command checks network connectivity?', NULL),
(18, 2, 'Which file system is commonly used in Windows?', NULL),
(19, 2, 'Which device converts digital to analog signals?', NULL),
(20, 2, 'Which tool monitors CPU usage in Windows?', NULL);

-- Quiz 3: COC3
INSERT INTO questions (id, quiz_id, question_text, correct_option_id) VALUES
(21, 3, 'Which OS is open-source?', NULL),
(22, 3, 'Which port is used for HTTPS?', NULL),
(23, 3, 'Which wireless standard is fastest?', NULL),
(24, 3, 'Which cloud service is SaaS?', NULL),
(25, 3, 'Which type of malware encrypts files?', NULL),
(26, 3, 'Which protocol is used for secure file transfer?', NULL),
(27, 3, 'Which device filters network traffic?', NULL),
(28, 3, 'Which IP version has 128-bit addresses?', NULL),
(29, 3, 'Which command lists running processes in Linux?', NULL),
(30, 3, 'Which protocol resolves hostnames to IP addresses?', NULL);

-- Quiz 1 Answers
INSERT INTO answers (id, question_id, answer_text, is_correct) VALUES
(1, 1, 'Twisted Pair', 1), (2, 1, 'Coaxial', 0), (3, 1, 'Fiber Optic', 0), (4, 1, 'USB', 0),
(5, 2, 'Internet Protocol', 1), (6, 2, 'Internal Process', 0), (7, 2, 'Integrated Program', 0), (8, 2, 'Input Procedure', 0),
(9, 3, 'Router', 1), (10, 3, 'Switch', 0), (11, 3, 'Hub', 0), (12, 3, 'Bridge', 0),
(13, 4, 'SMTP', 1), (14, 4, 'FTP', 0), (15, 4, 'HTTP', 0), (16, 4, 'DNS', 0),
(17, 5, 'Translate domain names', 1), (18, 5, 'Store passwords', 0), (19, 5, 'Encrypt data', 0), (20, 5, 'Monitor traffic', 0),
(21, 6, 'Star', 1), (22, 6, 'Ring', 0), (23, 6, 'Bus', 0), (24, 6, 'Mesh', 0),
(25, 7, 'Network Layer', 1), (26, 7, 'Transport Layer', 0), (27, 7, 'Data Link Layer', 0), (28, 7, 'Physical Layer', 0),
(29, 8, '80', 1), (30, 8, '443', 0), (31, 8, '21', 0), (32, 8, '25', 0),
(33, 9, 'Router', 1), (34, 9, 'Switch', 0), (35, 9, 'Hub', 0), (36, 9, 'Repeater', 0),
(37, 10, 'Presentation Layer', 1), (38, 10, 'Session Layer', 0), (39, 10, 'Network Layer', 0), (40, 10, 'Data Link Layer', 0);

-- Quiz 2 Answers
INSERT INTO answers (id, question_id, answer_text, is_correct) VALUES
(41, 11, 'ipconfig', 1), (42, 11, 'ping', 0), (43, 11, 'tracert', 0), (44, 11, 'netstat', 0),
(45, 12, 'Forward traffic to other networks', 1), (46, 12, 'Store local IP', 0), (47, 12, 'Encrypt data', 0), (48, 12, 'Monitor CPU', 0),
(49, 13, 'RAM', 1), (50, 13, 'ROM', 0), (51, 13, 'Hard Disk', 0), (52, 13, 'Flash', 0),
(53, 14, 'Drivers', 1), (54, 14, 'Games', 0), (55, 14, 'Office', 0), (56, 14, 'Photoshop', 0),
(57, 15, 'CMOS', 1), (58, 15, 'RAM', 0), (59, 15, 'CPU', 0), (60, 15, 'ROM', 0),
(61, 16, 'ALU', 1), (62, 16, 'CU', 0), (63, 16, 'Registers', 0), (64, 16, 'Cache', 0),
(65, 17, 'ping', 1), (66, 17, 'ipconfig', 0), (67, 17, 'tracert', 0), (68, 17, 'netstat', 0),
(69, 18, 'NTFS', 1), (70, 18, 'FAT32', 0), (71, 18, 'exFAT', 0), (72, 18, 'FAT16', 0),
(73, 19, 'Modem', 1), (74, 19, 'Router', 0), (75, 19, 'Hub', 0), (76, 19, 'Switch', 0),
(77, 20, 'Task Manager', 1), (78, 20, 'Device Manager', 0), (79, 20, 'Resource Monitor', 0), (80, 20, 'Event Viewer', 0);

-- Quiz 3 Answers
INSERT INTO answers (id, question_id, answer_text, is_correct) VALUES
(81, 21, 'Linux', 1), (82, 21, 'Windows', 0), (83, 21, 'macOS', 0), (84, 21, 'DOS', 0),
(85, 22, '443', 1), (86, 22, '80', 0), (87, 22, '21', 0), (88, 22, '25', 0),
(89, 23, 'Wi-Fi 6', 1), (90, 23, 'Wi-Fi 4', 0), (91, 23, 'Wi-Fi 5', 0), (92, 23, 'Wi-Fi 3', 0),
(93, 24, 'Google Workspace', 1), (94, 24, 'AWS EC2', 0), (95, 24, 'Dropbox IaaS', 0), (96, 24, 'Azure VM', 0),
(97, 25, 'Ransomware', 1), (98, 25, 'Spyware', 0), (99, 25, 'Trojan', 0), (100, 25, 'Adware', 0),
(101, 26, 'SFTP', 1), (102, 26, 'FTP', 0), (103, 26, 'HTTP', 0), (104, 26, 'SMTP', 0),
(105, 27, 'Firewall', 1), (106, 27, 'Router', 0), (107, 27, 'Switch', 0), (108, 27, 'Hub', 0),
(109, 28, 'IPv6', 1), (110, 28, 'IPv4', 0), (111, 28, 'IPX', 0), (112, 28, 'ARP', 0),
(113, 29, 'ps -aux', 1), (114, 29, 'top', 0), (115, 29, 'netstat', 0), (116, 29, 'kill', 0),
(117, 30, 'DNS', 1), (118, 30, 'DHCP', 0), (119, 30, 'FTP', 0), (120, 30, 'HTTP', 0);

UPDATE questions SET correct_option_id = 1 WHERE id = 1;
UPDATE questions SET correct_option_id = 5 WHERE id = 2;
UPDATE questions SET correct_option_id = 9 WHERE id = 3;
UPDATE questions SET correct_option_id = 13 WHERE id = 4;
UPDATE questions SET correct_option_id = 17 WHERE id = 5;
UPDATE questions SET correct_option_id = 21 WHERE id = 6;
UPDATE questions SET correct_option_id = 25 WHERE id = 7;
UPDATE questions SET correct_option_id = 29 WHERE id = 8;
UPDATE questions SET correct_option_id = 33 WHERE id = 9;
UPDATE questions SET correct_option_id = 37 WHERE id = 10;

UPDATE questions SET correct_option_id = 41 WHERE id = 11;
UPDATE questions SET correct_option_id = 45 WHERE id = 12;
UPDATE questions SET correct_option_id = 49 WHERE id = 13;
UPDATE questions SET correct_option_id = 53 WHERE id = 14;
UPDATE questions SET correct_option_id = 57 WHERE id = 15;
UPDATE questions SET correct_option_id = 61 WHERE id = 16;
UPDATE questions SET correct_option_id = 65 WHERE id = 17;
UPDATE questions SET correct_option_id = 69 WHERE id = 18;
UPDATE questions SET correct_option_id = 73 WHERE id = 19;
UPDATE questions SET correct_option_id = 77 WHERE id = 20;

UPDATE questions SET correct_option_id = 81 WHERE id = 21;
UPDATE questions SET correct_option_id = 85 WHERE id = 22;
UPDATE questions SET correct_option_id = 89 WHERE id = 23;
UPDATE questions SET correct_option_id = 93 WHERE id = 24;
UPDATE questions SET correct_option_id = 97 WHERE id = 25;
UPDATE questions SET correct_option_id = 101 WHERE id = 26;
UPDATE questions SET correct_option_id = 105 WHERE id = 27;
UPDATE questions SET correct_option_id = 109 WHERE id = 28;
UPDATE questions SET correct_option_id = 113 WHERE id = 29;
UPDATE questions SET correct_option_id = 117 WHERE id = 30;
