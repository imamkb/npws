# News Portal Website(NPWS)

During the 2018 odd semester, the college I currently study at taught us a subject called Software Engineering. For term work in that subject, we needed to use XAMPP to build a software to showcase our software development and managerial skills. XAMPP is basically a cross-platform FOSS stack package, containing Apache, MariaDB, and PHP, Perl interpreters. The code here, in this repository is a very stripped down version of the software that my teammate([hey Imam!](https://github.com/imamkb)) and I had built in a span of 48 hours.

## Introduction

NPWS is basically a simple **blog**, designed to give the viewers a **summarised article**(fed by administrators called _journalists_) in an easy to read HTML elements called **tiles**(_basically just divs with shadow_).

## Instructions

The setup should be quite straightforward for somebody who has already used XAMPP before. Here are the steps in a nutshell:
1. Install [XAMPP](https://www.apachefriends.org/download.html).
2. Move the whole `npws` folder into the `htdocs` folder in the XAMPP installation directory.
3. Enable the Apache and MySQL services on the XAMPP Control Panel.
4. Go to the PHPmyAdmin control panel, create a database(named **npws**) and **import** the `npws.sql` dump file into that database.

## Conclusion

This code has been written quickly and haphazardly. While we currently do not have plans of actually making any stable version of this code, we may reconsider some day in the future.

>UPDATE: This is the future me, and I have made a few minor fixes to mitigate a few security critical issues. Made these fixes out of interest.

>UPDATE: Future me again, and as a proof of concept, I implemented a rudimentary and anonymous user profiling/fingerprinting mechanism in order to insert views and triggers into the database. Yes, I have learnt which parts should be coded in the DB, and which parts in the backend.