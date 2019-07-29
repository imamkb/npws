# News Portal Website(NPWS)

During the 2018 odd semester, the college I currently study at taught us a subject called Software Engineering. For term work in that subject, we needed to use XAMPP to build a software to showcase our software development and managerial skills. XAMPP is basically a cross-platform FOSS stack package, containing Apache, MariaDB, and PHP, Perl interpreters. The code here, in this repository is a very stripped down version of the software that my teammate(_who still hasn't joined GitHub as of this commit_) and I had built in a span of 48 hours.

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