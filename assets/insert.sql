CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unread', 'read') DEFAULT 'unread',
    FOREIGN KEY (sender_id) REFERENCES Members(member_id),
    FOREIGN KEY (recipient_id) REFERENCES Members(member_id)
);
