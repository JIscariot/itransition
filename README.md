Itransition test
========================
Import csv products list to database. Based on Symfony 3.

# Run
- bin/console products:update 
- bin/console products:update test

## Test
- vendor/bin/simple-phpunit

### TODO
- Import data from csv using LOAD DATA INFILE
