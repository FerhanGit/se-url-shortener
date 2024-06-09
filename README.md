# Task: Implement a URL Shortener Service

## Description: You have to create a simple URL shortener service similar to bit.ly or tinyurl.com. Users can input a long URL, and your service will generate a short, unique URL (like “bit.ly/abc123”). When the short URL is opened the user must be redirected to the original URL.

## Requirements:
- Create small project using plain PHP, custom framework or any popular framework you like.
- Crete a page to generate a unique short URL for given URL. 
- Store the mapping between the short code and the original URL.
- When someone accesses the short URL (e.g., https://yourdomain.com/abc123), redirect them to the original long URL.
- The short URL is valid 1 week.
- Handle the following cases:
- Same long URL is submitted multiple times.
- URL submitted is not valid URL.
- Short URL has expired.
- The short URL is always unique to the URL given.
- Example Usage:
- User submits: https://www.example.com/very-long-url-that-needs-shortening
- Your service generates a short URL: https://yourdomain.com/abc123
- When someone accesses https://yourdomain.com/abc123, they are redirected to the original URL.


## Implementation details
- Symfony 7.1 is used for the project with additional packages for Twig, Doctrine ORM, Forms, Validator, Maker and more
- For the Data Base is used SQL Lite
- All routes are described as Controller method annotations
- Main Controller is src/Controller/UrlShortenerController.php
- A specfic Form Type - src/Form/UrlShortenerType.php, has been created for the need of the project, which is handling submitting original Long URL, which is later linked to the newly created Short URL.
- Our entity class - src/Entity/UrlShortener.php, contains all needed validations (again described with annotations) for its field, which is used for validating on Form submittion.
For example: 
```#[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Url(
        message: 'The url {{ value }} is not a valid url',
    )]
    private string $longUrl;
```
- For frontend part it is used Twig templates
- For the styling is used Bootstrap 5
- All DB schema changes are implemented and executed via Doctrine migrations
- Short URL validity time is described in the .env file, located at the root of the project - the validity time is in seconds and can be changed when testing to a smaller value - for example:
>SHORT_URL_VALID_TIME=30

### Requirements to install the project locally 
- Need to have installed GIT on your host machine
- Fulfill the requirements described here - https://symfony.com/doc/current/setup.html#technical-requirements
 
### How to checkout, install and run
- Clone the project by running the following command:
```
git clone https://github.com/FerhanGit/se-url-shortener.git
```
- Go inside the newly created project folder
- Run:
```
composer install
```
- if you have installed Symfony CLI (https://symfony.com/download) you can then proceed with starting the Build-In Symfony server
```
symfony server:start
```
- Open in your browser the provided Local URL (http://127.0.0.1:8000/)
- Additionally you can open your preffered terminal, go to the project folder and run available tests by executing:
```
php bin/phpunit
```

### App features
- The main page is located on http://127.0.0.1:8000/ (if you use the build-in symfony server)
- It opens a page with a form, where you can input your original long URL and after sumbitting you will have generated a corresponding Short URL.
- The records for created Short URLs and their original Long URLs are stored in the Data Base, along with the Created At date and autogenerated unique ID
- There is a Data Base constraint not allowing to insert the same Long URL twice - if you try - you will get an error message on Form Submit
- On Form Submit we validate the inserted URL to not be empty and to be a valid URL - using Symfony Validator component
- When the original URL is submtted successfully (and the corresponding Short URL is generated) - we redirect the user to the page where he/she can see the details about the new record - For example http://127.0.0.1:8000/url/shortener/17. 
- You are able to quickly copy the newly generated Short URL, by hitting the provied `Copy the URL` button. There you have also a button to create a new Short URL - in case you want so.
- In addition there is a page for listing all available records available in the Data Base. You can see all details regarding each of them and if some Short URL has expired - its row is colored in red.
- There is a Delete button for each record in the listing page, using wich you can delete any of them - especially if it is expired and you want to be able to create a new Short URL, corresponding to your original Long URL. It is also available a button to redirect you to the page for creation a new Short URL
- Automated PHP Unit and End to End tests are developed and can be executed by running ***php bin/phpuinit*** command on your terminal

### Usage of the Short URL
- When you open with your browser the generates Short URL you will be redirected to the original URL
- If the requested Short URL is expired - you will get a descriptive error message on top of the page and a button `Generate new Short URL`, which you can use to create a new one

