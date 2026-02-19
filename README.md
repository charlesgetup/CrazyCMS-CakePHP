# CrazyCMS-CakePHP – Legacy CMS Platform Study Repository

[![CakePHP](https://img.shields.io/badge/CakePHP-2.4.5-red)](https://cakephp.org/)
[![PHP](https://img.shields.io/badge/PHP-5.3%2B-purple)](https://www.php.net/)
[![ZeroMQ](https://img.shields.io/badge/ZeroMQ-4.0.3-blue)](http://zeromq.org/)
[![License](https://img.shields.io/badge/License-Study%20Only-lightgrey)](LICENSE)

## 📋 Overview

This repository contains a **legacy version of CrazyCMS** released exclusively for **educational and study purposes**. The codebase demonstrates a complex CMS implementation built on multiple technologies, showcasing enterprise-level architecture patterns from the CakePHP 2.x era.

## 🛠️ Technology Stack

### Core Framework
- **[CakePHP 2.4.5](http://www.cakephp.org)** – Rapid development PHP framework
- PHP 5.3+ with object-oriented programming

### Message Queue System
- **[ZeroMQ 4.0.3](http://zeromq.org/)** – High-performance message queue processor
- **[ZeroMQ PHP binding](https://github.com/mkoppanen/php-zmq.git)** – PHP interface for ZeroMQ

### System-Level PHP Extensions
- **[Pcntl](http://php.net/pcntl)** – Process Control for Unix-style:
  - Process creation and management
  - Program execution control
  - Signal handling
  - Process termination
- **[POSIX](http://php.net/posix)** – POSIX standard functions interface

### Additional Technologies
- **ZMQ** – Advanced messaging patterns
- **Postfix** – Mail transfer agent integration
- **Online Virus Check Library** – File scanning capabilities

## ✨ Key Features Implemented

- **Email Marketing**
  - Bulk email campaigns
  - Newsletter management
  - Subscriber list handling
  - Email template system
  
- **Live Chat System**
  - Real-time messaging
  - Agent/client communication
  - Chat history and transcripts
  - Queue management
  
- **Payment Processing**
  - Multiple payment gateway integration
  - Transaction management
  - Invoice generation
  - Subscription handling
  
- **Task Management**
  - Project organization
  - Task assignment and tracking
  - Deadline management
  - Team collaboration tools
  
- **Online Web Development**
  - In-browser code editing
  - Development environment management
  - Deployment tools
  - Version control integration

## 🎯 Purpose

This repository serves as an **educational resource only**. It's designed to help developers understand:

- Legacy CakePHP 2.x architecture patterns
- Message queue implementation with ZeroMQ
- System-level PHP programming (Pcntl, POSIX)
- Complex CMS feature integration
- Email marketing system architecture
- Real-time chat implementation
- Payment gateway integration patterns
- Task management system design
- Virus scanning integration
- Postfix email server integration

## ⚠️ Important Notice

**Commercial Use Prohibited**
- This code is for **study purposes only**
- No commercial usage is permitted
- Not intended for production deployment
- Legacy code may contain security vulnerabilities
- The repository owner bears no responsibility for any consequences arising from the use of this code

## 💡 For Developers & Learners

If you're interested in:
- Legacy CakePHP application architecture
- Message queue systems with ZeroMQ
- System programming in PHP
- Enterprise CMS feature implementation
- Understanding pre-modern PHP practices
- Migration strategies for legacy systems

This repository provides a solid foundation for study and experimentation.

## 📋 Requirements

- PHP 5.3 or higher (5.4+ recommended)
- MySQL 5.1 or higher
- ZeroMQ 4.0.3
- PHP extensions: pcntl, posix, zmq
- Postfix mail server
- Virus scanning library (ClamAV or similar)
- Web server (Apache/Nginx)

## 📞 Contact

Questions or suggestions about this educational project?
Feel free to open an issue or reach out through GitHub.

---

## 🚀 Getting Started

1. Clone this repository
2. Install PHP 5.3+ with required extensions (pcntl, posix)
3. Install and configure ZeroMQ 4.0.3
4. Install PHP ZMQ binding
5. Set up MySQL database
6. Configure Postfix for email features
7. Install virus scanning library
8. Explore the source code to understand legacy implementations
9. Study the patterns for:
   - CakePHP 2.x architecture
   - Message queue systems
   - Process control in PHP
   - Complex feature integration

---

## ⚠️ Legacy Code Considerations

- This code is from the **CakePHP 2.x era** (circa 2013-2015)
- Modern PHP practices may differ significantly
- Contains **potential security vulnerabilities** not suitable for production
- Demonstrates historical approaches to complex problems
- Excellent for learning **migration strategies**

---

## 📚 Learning Objectives

By studying this repository, you can understand:

1. **CakePHP 2.x MVC Architecture**
   - Controllers, Models, Views structure
   - Component and behavior systems
   - Request handling patterns

2. **Message Queue Systems**
   - ZeroMQ implementation patterns
   - Asynchronous processing
   - Worker queue management

3. **System Programming in PHP**
   - Process control with Pcntl
   - POSIX functions usage
   - Signal handling

4. **Enterprise Feature Integration**
   - Email marketing systems
   - Real-time communications
   - Payment processing
   - Task management

---

*Remember: This is a legacy codebase for educational purposes only. Not intended for modern production use.*