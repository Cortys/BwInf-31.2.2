BwInf-31.2.2
============

Solution for the second task of the second round of the 31st BwInf (national CS competition in Germany).

## Installation ##

Open the `index.php` with an Apache web server. That's all.

## Requirements ##

- Apache web server
- PHP5 installed
- PHP SPL class library installed
- PHP JSON library installed
- A browser. Obviously. But please do not use IE. Please...

## What to do with it? ##

The basic principle is that 100 autonomic robots have to meet at one point without any possible communication.
The only information each robot has is its radar, which can detect robots that are max. 200 meters away.

You can prove that the solution to that problem is to let every single robot walk towards the center of the smallest enclosing circle around the robots it can see.
Actually quite simple. For the calculation of the smallest enclosing circle I am using the [Algorithm of Skyum](http://ojs.statsbiblioteket.dk/index.php/daimipb/article/download/6704/5821) and the therefor needed convex hull is determined by the Graham Scan.
That should explain everything you may or may not want to know about my solution.

## Why is it German? ##

!(http://cdn.rsvlts.com/wp-content/uploads/2013/11/deal-with-it.jpg)
