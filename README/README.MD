# AQPlatform - Web Application with Modularity in Mind

**Note:** AQPlatform is currently under development as a Document Control System.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Purpose and Objectives](#purpose-and-objectives)
- [Getting Started](#getting-started)
- [Contribution](#contribution)
- [License](#license)
- [Contact](#contact)

## Introduction

**AQPlatform** is a web application designed with modularity in mind. It is built to offer a flexible and scalable platform for a wide range of functionalities while maintaining clean and organized code. This README provides an overview of the scope, purpose, and objectives of the application.

## Features

### Core Functionality

- User Management: Create, update, delete, and view user profiles.
- CRUD Operations: Implement Create, Read, Update, and Delete operations for various entities.
- Dedicated User Profiles: Allow users to update their information.
- Logging and Audit Trail: Capture and log user actions for auditing purposes.
- Workflow Creator: Create and manage workflows that connect with various modules.

### Modularity

- Organized Modules: The application is structured with clear separation between core functionality and modules, enhancing code maintainability and scalability.
- Isolated Assets: CSS, JavaScript, images, and fonts are organized within each module, making it easy to manage resources specific to each feature.
- Clean Code Structure: Each module maintains a separation between utility functions ("util") and JavaScript scripts ("script"), ensuring clean and organized code.
- Consistency: A consistent directory structure simplifies development and fosters collaboration among team members.

### Multi-Tenancy

- Shared Core Features: Core features such as user management, permissions, and groups are shared among multiple customers (tenants).
- Module Isolation: Each customer (tenant) has isolated modules, allowing for customization and configuration of their individual modules.
- Data Isolation: Tenant data is logically or physically separated to ensure data privacy and security.
- Authentication and Authorization: Authentication and authorization mechanisms handle access control for all tenants while maintaining isolation.
- Customization: Tenants can customize their modules to meet their specific needs, including user roles and access control.
- Centralized Management: Despite serving multiple tenants, the application is centrally managed and maintained.
- Scalability: The application is designed to scale horizontally to accommodate a growing number of tenants and users while maintaining performance.
- Reporting and Audit Trail: Audit trail and logging features track actions and events for each tenant separately, allowing for customized reporting and analysis.

## Purpose and Objectives

The main purpose of AQPlatform is to provide a robust and extensible web application framework that empowers developers to build a wide range of modules and features. The key objectives of the project include:

1. **Modularity**: Enable the development of separate, self-contained modules that can be added or removed without impacting the core application.

2. **Clean Code**: Maintain a clean and organized codebase that is easy to understand, extend, and maintain.

3. **User Management**: Facilitate user registration, authentication, and profile management with a dedicated user interface.

4. **Audit Trail**: Collect and log user actions to provide an audit trail for compliance and troubleshooting.

5. **Workflow Creation**: Offer a workflow creator tool that allows users to design custom workflows for their specific needs.

## Getting Started

To get started with AQPlatform, follow the installation instructions in the [documentation](/link-to-documentation) and refer to the specific module documentation for detailed usage guides.

## Contribution

Contributions to AQPlatform are welcome! If you'd like to contribute to the project, please refer to our [contribution guidelines](/link-to-contributing).

## License

This project is licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/). See the [LICENSE](/link-to-license) file for more details.

**License Summary:**

- **Attribution (CC BY 4.0)**: Users are required to give appropriate credit, provide a link to the license, and indicate if changes were made. Users may do so in any reasonable manner, but not in any way that suggests the licensor endorses them or their use.

## Contact

For questions, feedback, or support, please contact [joel.garcia@documentcontrolpr.com](mailto:joel.garcia@documentcontrolpr.com).

---

## Web App Structure

### Main Sections

- **Core**
- **Modules**

### Core Structure

- **assets**
  - **css**
  - **script**
  - **util**
- **config**
  - `config.php` (connection to the database)
- **public**
- **transactions**
  - `login.php`
  - `userTransaction.php`
  - ...

### Modules Structure

- **AttendanceTracker**
  - **assets**
    - **css**
    - **script**
    - **util**
  - **transactions**
    - `attendanceTransaction.php`
    - ...
- **HelpDesk**
  - **assets**
    - **css**
    - **script**
    - **util**
  - **transactions**
    - `helpDeskTransaction.php`
    - ...
