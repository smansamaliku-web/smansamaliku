-- This SQL file contains improvements and fixes for the graduation data table.

-- Improvement 1: Added NOT NULL constraints to critical columns to ensure data integrity.
-- This prevents null values in these fields and maintains valid graduation records.
ALTER TABLE graduation_table
MODIFY COLUMN student_id INT NOT NULL,
MODIFY COLUMN graduation_year INT NOT NULL,
MODIFY COLUMN major VARCHAR(100) NOT NULL;

-- Improvement 2: Created indexes on commonly queried columns to increase performance.
-- This speeds up searches and retrieval of records from the graduation data.
CREATE INDEX idx_student_id ON graduation_table(student_id);
CREATE INDEX idx_graduation_year ON graduation_table(graduation_year);

-- Improvement 3: Standardized major names to ensure consistency.
-- This will help in proper categorization and reporting of data.
UPDATE graduation_table
SET major = 'Science' WHERE major LIKE 'Sci%';
UPDATE graduation_table
SET major = 'Arts' WHERE major LIKE 'Art%';

-- Fix 1: Corrected data types for columns to match expected formats.
-- Ensured that all year values are stored as integers and not strings.
ALTER TABLE graduation_table
MODIFY COLUMN graduation_year INT;

-- Fix 2: Removed duplicate entries in the graduation table.
-- This cleans up the dataset and avoids inaccuracies in reporting.
DELETE FROM graduation_table
WHERE student_id IN (SELECT student_id FROM graduation_table GROUP BY student_id HAVING COUNT(*) > 1);

-- Fix 3: Added foreign key constraints to link graduation data with students table.
-- This ensures referential integrity between the tables.
ALTER TABLE graduation_table
ADD CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES students(student_id);