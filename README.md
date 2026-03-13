# SwiftTrace: forensic audit & active sanitization URL engine

## Overview
SwiftTrace is an advanced, high-performance URL shortening and security auditing platform designed to neutralize digital footprints and analyze connection integrity. Unlike conventional link shorteners, SwiftTrace operates as a privacy-centric gateway, performing real-time sanitization of incoming traffic and providing a comprehensive forensic dashboard for technical auditing.

## Core Features

### 1. Active Sanitization Engine (Anti-Tracking)
SwiftTrace features a proprietary sanitization module that performs atomic decomposition of HTTP query strings. It automatically identifies and purges cross-site tracking identifiers (CSTIs) and marketing metadata, including:

* **Advertising Identifiers:** `fbclid` (Facebook), `gclid` (Google Ads), `twclid` (Twitter/X), `msclkid` (Microsoft).
* **Marketing Metrics:** `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`.
* **Referral Metadata:** `ref`, `source`.

This process ensures that the redirection payload is stripped of tracking overhead, preserving the sender's privacy and breaking the chain of attribution across domains.

### 2. Passive Forensic Fingerprinting
The system implements a non-intrusive audit protocol to capture connection vectors without the use of persistent cookies. Each interaction is analyzed to extract:

* **Technical Identity:** Native screen resolution, OS architecture, and browser engine specifications.
* **Traffic Origin:** Deep Referer analysis to distinguish between organic social traffic, direct access, and automated agents (Bots).
* **Network Metadata:** IP-based geolocation and regional localization settings.

### 3. Security & Infrastructure Hardening
SwiftTrace follows the principle of *Privacy by Design*. The backend is secured through:

* **Server-Level Restrictions:** Hardened `.htaccess` rules to prevent unauthorized directory traversal and protect core configuration files.
* **Relational Integrity:** A normalized MySQL architecture designed for high-granularity traffic logging and forensic traceability.
* **Access Control:** A restricted administrative dashboard featuring session-level security and IP-based masking for data protection.

## Technical Architecture

The application is built on a robust PHP/MySQL stack, prioritizing low-latency redirections and efficient data processing.

* **Backend:** PHP 8.x (Logic and Sanitization)
* **Database:** MySQL / MariaDB (Persistent Storage)
* **Server:** Apache HTTP Server with custom `.htaccess` orchestration
* **Typography:** Cardo Serif (Professional Documentation Identity)

## Installation & Deployment

1.  Clone the repository to your server's root directory.
2.  Configure database credentials in the `config.php` file.
3.  Import the SQL schema to initialize the auditing tables.
4.  Verify that the `.htaccess` module is active to enable the redirection and security logic.

---

### Extended Documentation
For a detailed breakdown of the forensic architecture and advanced security protocols, please visit the official technical documentation:

[https://jlasenjo-swifttrace.xo.je/info.php](https://jlasenjo-swifttrace.xo.je/info.php)
