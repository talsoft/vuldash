# vuldash (Vulnerability Dashboard)

Vuldash allows managing projects ethical hacking together with the group of pentester and the client, showing the problems concisely. Can generate business and technical reports, as also perform a life cycle of the project with the client.

## Custom Plugins
- You can create your own plugin for your tools and import the data into the platform.

## Reporting
- You can generate professional reports with the format of your templates. (en,es)

## Customer Features

- Allows your customers to perform
- Tracking and export of reported incidents.
- Online access of incidents at managerial and technical 

## Security Company Features

- Allows your administrators and pentester users to perform.
- Creating users for customer projects and pentester users.
- Tracking in different states of the incidents found in an ethical hacking project.

# Authors

- Andrés Gaggini @AndresGaggini
- Leandro Ferrari @avatar_leandro www.talsoft.com.ar

# Requirements

- Apache php 5.6 (modules php5.6-gd php5.6-json php5.6-mbstring php5.6-xsl php5.6-zip) 
- Mysql 5.5 

# Recommendations

- Use filter allow from ip origin at .htaccess
- Install certificadte SSL to use the system, (eg. https://letsencrypt.org)


# Initial Setup

- Create directory vuldash and inside the directory dashboard
- Copy the content folder in the virtualhost directory of your web server into dashboard (eg: /var/www/vuldash/dashboard)

# Database

- Edit file application/config/database.php to change credentials conection.
- Create database and user vuldash
- Import vuldashdb.sql into mysql database

# Application setup

- Edit file application/config/app.php to change setting application.
- Change config of server mail account to send notificacion of activation accounts.
- Change values google_site_key and google_secret_key to use Captha Google.


# Usage

- Access vuldash (eg: http://localhost/vuldash/dashboard)
- First login with user: admin@vuldash.com pass: admin
- Add users of vuldash with roles administrator and tester. 
- Add the system tables of type of incidents state, project type, project state, incidents type and objetive state.
- Add clients and users clients.
- Assign a project to a client.

# From projects

- Import XML nmap results 

# From Incident

- Import XML Zap proxy Alerts
- Import XML Openvas report (coming soon)

# Templates Reports

- You can change report template into directory vuldash/assets/odt-templates/
- Add _en or _sp at the end of the name from choose that languages 

# Tips

- You have error in generate report or incidents?
  1. Check folder "tmp" in the site root and set permission for write.
  2. Check the report language in the proyect properties with the name of report template

