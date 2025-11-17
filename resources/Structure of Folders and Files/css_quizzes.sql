-- =================================================
-- QUIZZES
-- =================================================
INSERT INTO quizzes (id, title, description) VALUES
(1, 'COC1', 'Install and Configure Computer Systems'),
(2, 'COC2', 'Set Up Computer Networks'),
(3, 'COC3', 'Maintain and Repair Computer Systems and Networks');

-- =================================================
-- QUESTIONS
-- =================================================
-- COC1
INSERT INTO questions (id, quiz_id, question_text) VALUES
(1,1,'What tool is used to test the power output of a PSU?'),
(2,1,'Which component is considered the brain of the computer?'),
(3,1,'What type of cable is used for SATA drives?'),
(4,1,'Which is a proper step before working inside a computer?'),
(5,1,'Which slot is used for a modern graphics card?'),
(6,1,'What does BIOS stand for?'),
(7,1,'What is the first device the BIOS checks when booting?'),
(8,1,'Which software is required after installing Windows?'),
(9,1,'What is the main purpose of thermal paste?'),
(10,1,'Which command is used to check system info in Windows CMD?');

-- COC2
INSERT INTO questions (id, quiz_id, question_text) VALUES
(11,2,'What does IP stand for?'),
(12,2,'How many bits are in an IPv4 address?'),
(13,2,'Which command checks network connectivity?'),
(14,2,'What tool is used to crimp RJ45 connectors?'),
(15,2,'Which cable type is most commonly used in LANs?'),
(16,2,'Which network topology uses a central device?'),
(17,2,'Which device assigns IP addresses automatically?'),
(18,2,'What is the default subnet mask for Class C?'),
(19,2,'What protocol is used for domain name resolution?'),
(20,2,'Which IP address is reserved for loopback testing?');

-- COC3
INSERT INTO questions (id, quiz_id, question_text) VALUES
(21,3,'What is the proper tool for cleaning dust inside a PC?'),
(22,3,'Which component failure often causes a "no display" issue?'),
(23,3,'Which command repairs corrupted system files in Windows?'),
(24,3,'Which is a sign of a failing hard drive?'),
(25,3,'What is the safe way to remove malware?'),
(26,3,'What should be checked first when a PC won’t power on?'),
(27,3,'Which tool is best for system temperature monitoring?'),
(28,3,'What type of maintenance includes cleaning and inspection?'),
(29,3,'Which is a common cause of overheating?'),
(30,3,'What component stores the BIOS settings?');

-- =================================================
-- QUESTION_OPTIONS
-- =================================================
-- COC1
INSERT INTO question_option (question_id, option_text) VALUES
(1,'Multimeter'),(1,'Crimping tool'),(1,'Loopback plug'),(1,'RJ45 tester'),
(2,'CPU'),(2,'RAM'),(2,'SSD'),(2,'PSU'),
(3,'7-pin data cable'),(3,'40-pin IDE ribbon'),(3,'Ethernet cable'),(3,'USB cable'),
(4,'Disconnect the power source'),(4,'Turn off monitor only'),(4,'Remove BIOS battery'),(4,'Format the hard drive'),
(5,'PCIe x16'),(5,'AGP'),(5,'PCI'),(5,'IDE'),
(6,'Basic Input Output System'),(6,'Binary Input Output Setup'),(6,'Basic Internal OS'),(6,'Boot Input Output System'),
(7,'Boot device'),(7,'Monitor'),(7,'Printer'),(7,'Network interface'),
(8,'Device Drivers'),(8,'Video editing software'),(8,'Game launcher'),(8,'Media player'),
(9,'Transfer heat from CPU to heatsink'),(9,'Store data'),(9,'Clean components'),(9,'Power the CPU fan'),
(10,'systeminfo'),(10,'ipconfig'),(10,'tasklist'),(10,'chkdsk');

-- COC2
INSERT INTO question_option (question_id, option_text) VALUES
(11,'Internet Protocol'),(11,'Internal Process'),(11,'Internet Provider'),(11,'Input Packet'),
(12,'32 bits'),(12,'16 bits'),(12,'64 bits'),(12,'48 bits'),
(13,'ping'),(13,'ipconfig'),(13,'tracert'),(13,'netstat'),
(14,'Crimping tool'),(14,'Multimeter'),(14,'Screwdriver'),(14,'Punch-down tool'),
(15,'UTP Cat5e/Cat6'),(15,'Coaxial cable'),(15,'Fiber ribbon'),(15,'IDE cable'),
(16,'Star topology'),(16,'Ring topology'),(16,'Bus topology'),(16,'Mesh topology'),
(17,'DHCP server'),(17,'DNS server'),(17,'Web server'),(17,'FTP server'),
(18,'255.255.255.0'),(18,'255.255.0.0'),(18,'255.0.0.0'),(18,'255.255.255.255'),
(19,'DNS'),(19,'DHCP'),(19,'TCP'),(19,'FTP'),
(20,'127.0.0.1'),(20,'192.168.0.1'),(20,'0.0.0.0'),(20,'255.255.255.255');

-- COC3
INSERT INTO question_option (question_id, option_text) VALUES
(21,'Compressed air blower'),(21,'Vacuum cleaner'),(21,'Brush'),(21,'Fan'),
(22,'RAM'),(22,'USB port'),(22,'Keyboard'),(22,'LAN cable'),
(23,'sfc /scannow'),(23,'format c:'),(23,'diskpart clean'),(23,'shutdown /r'),
(24,'Clicking noises'),(24,'Fast boot'),(24,'Bright display'),(24,'High FPS'),
(25,'Run an antivirus scan'),(25,'Delete system32'),(25,'Unplug the monitor'),(25,'Rename the virus file'),
(26,'Power cable & switch'),(26,'Video driver'),(26,'Mouse settings'),(26,'Wallpaper'),
(27,'HWMonitor'),(27,'Paint'),(27,'Notepad'),(27,'CMD'),
(28,'Preventive maintenance'),(28,'Corrective maintenance'),(28,'Predictive maintenance'),(28,'System overhaul'),
(29,'Dust buildup'),(29,'New GPU installed'),(29,'Full storage'),(29,'Updated drivers'),
(30,'CMOS battery'),(30,'CPU fan'),(30,'USB drive'),(30,'SSD');

-- =================================================
-- ANSWERS
-- =================================================
-- COC1
INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(1,'Multimeter',1),(2,'CPU',1),(3,'7-pin data cable',1),(4,'Disconnect the power source',1),
(5,'PCIe x16',1),(6,'Basic Input Output System',1),(7,'Boot device',1),(8,'Device Drivers',1),
(9,'Transfer heat from CPU to heatsink',1),(10,'systeminfo',1);

-- COC2
INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(11,'Internet Protocol',1),(12,'32 bits',1),(13,'ping',1),(14,'Crimping tool',1),
(15,'UTP Cat5e/Cat6',1),(16,'Star topology',1),(17,'DHCP server',1),(18,'255.255.255.0',1),
(19,'DNS',1),(20,'127.0.0.1',1);

-- COC3
INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(21,'Compressed air blower',1),(22,'RAM',1),(23,'sfc /scannow',1),(24,'Clicking noises',1),
(25,'Run an antivirus scan',1),(26,'Power cable & switch',1),(27,'HWMonitor',1),(28,'Preventive maintenance',1),
(29,'Dust buildup',1),(30,'CMOS battery',1);
