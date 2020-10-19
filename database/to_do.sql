-- -------------------Database Name: to_do--------------------
DROP TABLE IF EXISTS Task;
DROP TABLE IF EXISTS Category;

-- Create a new Category Table
CREATE TABLE IF NOT EXISTS Category ( 
    CategoryID int(10) PRIMARY KEY AUTO_INCREMENT,    
    CategoryName varchar(30) NOT NULL,
    IsDeleted boolean NOT NULL DEFAULT 0
);

-- Create a new Task Table
CREATE TABLE IF NOT EXISTS Task (
    TaskID int(10) PRIMARY KEY AUTO_INCREMENT,
    CategoryID int(10) NOT NULL,
    TaskName varchar(30) NOT NULL,
    DueDate date NOT NULL,
    IsComplete boolean NOT NULL,
    CONSTRAINT FOREIGN KEY FK_Task_Category (CategoryID) REFERENCES Category(CategoryID)
);

-- Seed some data in Category table
INSERT INTO Category (CategoryID, CategoryName)
VALUES
(NULL, 'Chores'),
(NULL, 'Homework');

-- Seed some data in Task table
INSERT INTO Task (TaskID, CategoryID, TaskName, DueDate, IsComplete) 
VALUES 
(NULL, 1, 'Clean-up my room', '2020-10-16', 0),
(NULL, 2, 'Read Book on SQL', '2020-10-16', 0);



