# Introduction
TimeClock is a web application developed for the use in concrete5. This simple time keeping app allows businesses to have their employees clock their time without the hassle of buying expensive time keeping hardware.

This app is entirely contained in the hosted website and can run on any device. The app contains two main features: the time clock and the ticketing system. The time clock allows users to clock in and clock out (With  a description of work done) and shows them their last 15 clock entries. The ticketing system is there for when employees forget to either clock in or clock out. It allows them to submit a hours difference and an explanation as to why the need to submit the ticket. <br />

The administrative side of this app has quite a few different features. The first one is the Clock Manager which allows administrators to view all of the clock entries for a given period, the current hour totals for each employee, and the current status of the employees (if they are clocked in or not). This page also allows them to download an Excel spreadsheet of the current pay period. This spreadsheet contains the entire database for the current time period (which includes: useranme, clock in and clock out time, description, and hours worked0). The other option that this page has is to start a new pay period which moves the contents of the current clock database to the historical data database and dumping the current database. <br />

The next page is the Ticket Manager. This gives administrators the ability to review and make decisions on user submitted tickets. If they accept a ticket then it will be inserted into the current clock database with the preface "Ticket:" The Ticket Manager also has the option for the administrator to download a copy of the database for record purposes. <br />

Next is the Edit Users (may turn into User Manager) page. This allows administrators to assign full names to people so they can be recognized in the clocking system. <br />

Finally there is the Historical Data page. This page contains all the clock entries that have ever been entered in the site. This page is also searchable based on User ID or username. This page also has the capability to download the entire database for record keeping purposes. <br />

To see what these pages look like, please see the Included Sub Pages and Blocks section. <br />

## Download and Installation
Currently, to install this app on your install of concrete5 you have to either download or clone this repository and manually transfer the timeclock directory (be sure not to copy the top level directory as it contains extra files and concrete5 will not recognize it) into the packages folder of your site. Once that is done go to 'Add Functionality' and install it through the dashboard. Once this app reaches v1.0 you will be able to install the app through the concrete5 marketplace.


## Version Roadmap/History
### v0.7
  Development
### v0.8 (Current)
  Push to Master branch and release to select group for testing
### v0.9
  Submission to the concrete5 review board
### v1.0
  Release into the concrete5 marketplace


## Included Sub Pages and Blocks

### Clock Manager
![clock manager](/readmeimages/clockmanager.png)

### Ticket Manager
![ticket manager](/readmeimages/ticketmanager.png)
### Edit Users
![edit users](/readmeimages/editusers.png)
### Historical Data
![historical data](/readmeimages/historicaldata.png)
### TimeClock Block
![time clock block](/readmeimages/timeclockblock.png)
### Ticket Submission Block
![Tickets](/readmeimages/ticketsubmissions.png)
