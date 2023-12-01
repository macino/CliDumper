# CliDumper
PHP Cli dumping library, duh.

## tl;dr
cause I'm cool ik. Oh no I've said it. whatever.

A relatively small library to dump complex php data structures (array, objects but scalar values too) in a more compact way with a bit more options to customize it. The references to object / methods can be found after my "What does it do" epos. No greek heroes were harmed during the development.

## No, but rly. What problems does it solve
even if you dont have it, you might get it.

How do you format your output for processing logs. And I don't mean low level runtime logs. I mean process logs that tell you, how well is your application/s behaving and what do they do. Do you use echo? What about large and complex data like an array or an object? Now is the tripple battle of var_dump, var_export and print_r. Or maybe you like some more humanly readable structure (the promo said so) and you use something clever like json. Or maybe you are just suicidal and even in this case you use echo (go and find some help please or just go out and touch some grass).

Yeah, nothing feels compact enough not to make log of complex processes feel like 90% braces, brackets, quotation marks and columns. Or even some syntactic sugar like types. And do you need all that?

I was searching for something particular in these logs. And as I don't mind searching through one-liners of nginx or php logs, I most definitely hate searching through log file full of var_dumps or var_exports where searching by a keyword or id in less meant scrolling a few sites around to find the correct set of properties you are checking. Like thonse 100 lines could have been 3 if the formatting was correct. But I have no intention to go over every possible dumping of large internal data and formatting it. And also, it can be formatted better for every case of this specific problem.

There can be format, that doesn't need to be parsed (although it can be). And it also would be compact but still showing all the sweet data you got in your app. (was a remark on "some sweet vowels" from IT crowd. I'm old and you are dirty).

We have a lot to sacrifice from previous formats and still maintain readability. We can then structure it in a way, that is obvious to human eye and use some tricks from stone age to make it even more readable and hip. The clever trick is color. I bet my lunch money that almost every terminal you are connected to can do colors (unless you work for a bank or the government)

## Enough of the side story and here we go. How
does it solve it ie what does it do.

- compact output
- customizable output
- highlights of scalar types

## Instalation

- composer instalation

## Usage

- usage example

## Methods

- CliDumper
- CliFormatter

