--@block
CREATE TABLE IF NOT EXISTS `projects` (
    project_id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_name varchar(300)
);
INSERT INTO `projects` (project_id, project_name)
VALUES (
        1,
        '102413 AfricanLII - Laws Africa Document Editing'
    ),
    (
        2,
        '102587 Welocalize - Hausa Translation Nigeria'
    ),
    (3, 'AccuLynx - Data Mapping FTE Team'),
    (4, 'AfricanLII-Gazette processing'),
    (
        5,
        'Ames - Hadden transcription of 18th century legal texts'
    ),
    (6, 'Ancestry - French Census Records'),
    (
        7,
        'Ancestry - Newspaper Clipping Categorization'
    ),
    (8, 'Bovitz'),
    (9, 'Company Downtime - NBO'),
    (10, 'Cornell Roper-iPoll Data Entry'),
    (
        11,
        'Cornell University Roper Center- Document Preparation'
    ),
    (
        12,
        'Innovative Document Imaging (IDI) Project Management'
    ),
    (13, 'InPlay - Web Research Fall Schedules'),
    (
        14,
        'JWG - Content Conversion, Web Tracking & QA 2022'
    ),
    (15, 'Newspapers.com'),
    (16, 'Nor1 - Implementation'),
    (
        17,
        'Picturae - Smithsonian NMAH Ledgers Transcription'
    ),
    (
        18,
        'Picturae - USDA National Arboretum Herbarium Digitization'
    ),
    (
        19,
        'ResiShares-Single Family Home Image Evaulation'
    ),
    (20, 'Salix Call Centre'),
    (21, 'Salix FTE Project'),
    (22, 'Ukraine BMD Church Records'),
    (23, 'Visible Charges'),
    (24, 'Welocalize - Swahili Translation');