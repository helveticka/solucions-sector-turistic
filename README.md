# Solucions pel Sector Turístic - 21786

## Content
In this repository there are six directories, where I store each one of the subject assigned deliverables. Together they build a full hotel distribution ecosystem: a PMS, a reservation webservice, a channel manager and a booking engine.

### P1: PMS database
This deliverable is the MySQL database of the property management system (PMS45609588). It stores the core schema of a hotel chain: hotels, rooms and room types, clients and companions, reservations, check-ins, board types and tariffs.

### P2: Reservation webservice
This deliverable is a webservice written in plain Node.js, using mysql2 and the http module without any framework. It acts as the central reservation system (CRS), exposing the PMS availability over HTTP in JSON and listening on port 3000.

### P3: Reception desktop app
This deliverable is a desktop reception application built with Python and Tkinter. It connects directly to the PMS to manage clients, reservations and check-ins from the hotel front desk.

### P4: Channel manager
This deliverable is a full-stack channel manager with its own database (CHANNEL45609588). The backend is a Flask REST API and the frontend is built with Vue 3 and Vite, letting hotels manage their availability across the distribution.

### P5: Booking engine
This deliverable is a customer-facing booking engine coded in PHP. It consumes the CRS from S2 to search availability and complete a reservation, with its own web-user authentication linked to the PMS clients.

### P6: Integrated booking engine
This final deliverable extends the booking engine so it integrates the PMS, the channel manager and the CRS at once, closing the distribution loop between the three systems.

## Author
Developed by [Harpo Joan](https://github.com/helveticka)

## License
This repository is licensed under a Creative Commons Attribution-NonCommercial 4.0 International License.
Copyright (c) 2026 Harpo Joan
