# TV Show Alert
 [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT) 
 ![Tests](https://github.com/asefsoft/tv-alert/actions/workflows/laravel.yml/badge.svg)
 
 ![Tests](https://img.shields.io/badge/PHPInsights%20%7C%20Code%20-93%25-success.svg)
![Tests](https://img.shields.io/badge/Complexity%20-83%25-success.svg)
![Tests](https://img.shields.io/badge/Architecture%20-100%25-success.svg)
![Tests](https://img.shields.io/badge/Style%20-98%25-success.svg)
 
![image](https://github.com/asefsoft/tv-alert/assets/46367425/e46026e1-30f1-4cf5-832a-ef36ce2dc25b)

TV Show Alert is an open-source project that allows users to stay informed about new episodes of their favorite TV series. Users can receive notifications via email or check their personalized timeline on the website to see their subscribed shows and upcoming episodes. 

The project is developed using Laravel, Livewire 3, and Tailwind CSS, offering a smooth, AJAX-driven user experience. It leverages Laravel Scout for fast searching and utilizes the Laravel Data package for Data Transfer Objects (DTOs). The codebase is fully tested with PHPUnit, and GitHub Actions ensures automatic testing after each push to GitHub.

You can see live version of this app here: [Series Alert](https://series-alert.ir)

## Features

- **Episode Notifications:** Receive email notifications about new episodes of your subscribed TV shows.
- **Personalized Timeline:** Access a user-friendly timeline on the website to view your subscribed shows and upcoming episodes.
- **Today's Shows:** Quickly check all TV shows with new episodes scheduled for today.
- **Smooth User Experience:** AJAX requests provide a seamless and responsive user experience.
- **Fast Search:** Leveraging Laravel Scout, the system offers high-speed searching for TV shows and episodes.
- **Automate TV Show updates:** Fully automatically scan for new shows and get info of current shows in the background.
- **Data Transfer Objects:** Utilizes the Laravel Data package to efficiently manage data transfer objects.
- **Tested Codebase:** The project is fully tested with PHPUnit and analyzed by PHPInsights, ensuring reliability and stability.
- **Dockerized:** The project is dockerized and can become up and running very fast.

### Timeline
This is an example of your timeline in site:

![image2](https://github.com/asefsoft/tv-alert/assets/46367425/e07b7ddf-9fb4-46ee-a45b-99d419bf2731)

## Tech Stack

- Laravel: The project is built using the robust Laravel framework.
- Livewire 3: Real-time interactions are powered by Livewire 3.
- Tailwind CSS: The user interface is styled with the utility-first Tailwind CSS framework.
- Laravel Scout: Provides efficient and high-speed searching capabilities. [Document](https://medium.com/p/8cf31ae10dcc)
- Laravel Data: Offers a structured approach to Data Transfer Objects (DTOs).
- PHPUnit: The codebase is rigorously tested with PHPUnit.
- GitHub Actions: Automatic testing and continuous integration are enabled with GitHub Actions.

## Docker run
This project is Dockerized, allowing you to easily run it with the following command:

``docker-compose up``

This will make the project accessible at `localhost:3838`.

### Configuration
Before running the project with Docker, consider customizing the `.env` file located in the project root directory. This file allows you to configure settings based on your needs.

Also, you can have more settings in the `.env.example` file in the `src` directory.

### Deployment
For deployment purposes, refer to the `deployment` directory. This directory contains various configuration files for MySQL, Nginx, and PHP-fpm.

### Docker Services
The `docker-compose.yml` file defines several services that work together to run this application:
* app: The laravel backend app, built on a PHP-FPM Docker image.
* scheduler: This service is essentially a copy of the app service, but its purpose is solely to run the Laravel scheduler. This allows the scheduler to crawl for TV series data without interfering with the main app functionality.
* mysql: Database service that stores the application's data. It utilizes a separate storage volume named `mysql-data` for persistence.
* nginx: Web server that handles incoming web requests and directs them to the app service.

## Deep inside the code
In this article, I explain how I implemented Laravel Scout along with TNTSearch to enhance the project's search capabilities:

[Blazingly fast search with laravel scout & TNTSearch](https://medium.com/p/8cf31ae10dcc)

## Thanks
Thanks to [episodate.com](https://www.episodate.com) for their great api which allowed us to get updates info of tv-series.

Also thanks to [mailtrap.io](https://mailtrap.io) for their email systems which we used for sending email notifications.

I extend my heartfelt gratitude to the open-source community and all contributors who have helped make this project possible. Your support and contributions are greatly appreciated.

[Mostafa Asef](https://github.com/asefsoft)
## License

This project is open-source and available under the [MIT License](LICENSE).
