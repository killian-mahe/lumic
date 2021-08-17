<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/killian-mahe/lumic">
    <img src="logo.jpg" alt="Logo" width="1280" height="1280">
  </a>

<h3 align="center">Lumic</h3>

  <p align="center">
    An awesome application to bring your favourite sites together in one place.
    <br />
    <a href="https://github.com/killian-mahe/lumic"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://lumic.fr">View Demo</a>
    ·
    <a href="https://github.com/killian-mahe/lumic/issues">Report Bug</a>
    ·
    <a href="https://github.com/killian-mahe/lumic/issues">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgements">Acknowledgements</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

Lumic is a lightweight web application designed to bring your favourite sites together.
You can create your own dashboard and access all your applications in one click.

This application works like a link reducer. You can associate a name to your application link,
and access it directly through your custom URL (e.g. [https://www.youtube.com/watch?v=dQw4w9WgXcQ](https://www.youtube.com/watch?v=dQw4w9WgXcQ) -> [lumic.fr/yt](https://www.youtube.com/watch?v=dQw4w9WgXcQ))

Designed to be used by teams and organisations, you can also share your links with other members of your team or publish them to the world.

In the future, this application will allow for other advanced features such as tracking link activity or using temporary links.

![img_1.png](img_1.png)

### Built With

This section should list any major frameworks that you built your project using. Leave any add-ons/plugins for the acknowledgements section. Here are a few examples.
* [Vue.Js](https://vuejs.org/)
* [InertiaJS](https://inertiajs.com/)
* [Laravel](https://laravel.com)



<!-- GETTING STARTED -->
## Getting Started

This application is based on the Laravel framework and VueJS.
All instructions for installing and developing a Laravel application are also valid.

### Prerequisites

Before clonning the repository, you must have an **apache web server**, and a **database** installed on your computer.
You can use WampServer, XAMPP or any other web development bundle. Then you should install and update **npm** to the latest available version.

* npm
  ```sh
  npm install npm@latest -g
  ```

We strongly encourage you to use [Composer](https://getcomposer.org/) to install the Laravel application.

### Installation

1. Clone the repo in your Apache web server
   ```sh
   git clone https://github.com/killian-mahe/lumic.git
   ```
2. Install Composer packages
   ```sh
   composer install
   ```
3. Install NPM packages
   ```sh
   npm install
   ```
4. Copy the `.env.example` to `.env` and complete the variables
   ```dotenv
    APP_NAME=Lumic
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost
    
    LOG_CHANNEL=stack
    LOG_LEVEL=debug
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=lumic
    DB_USERNAME=my_username
    DB_PASSWORD=my_password
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=database
    SESSION_LIFETIME=120
    
    MEMCACHED_HOST=127.0.0.1
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_MAILER=smtp
    MAIL_HOST=mailhog
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=null
    MAIL_FROM_NAME="${APP_NAME}"
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1
    
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
   ```

### Use

1. Start the build watcher
    ```sh
    npm run watch
    ```
2. (optional) Start the Laravel built-in server
    ```sh
   php artisan serve
   ```
3. Before each commit or push, make a complete build using :
    ```sh
   npm run build 
   ```

<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/othneildrew/Best-README-Template/issues) for a list of proposed features (and known issues).


<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

Killian Mahé - [@killian-mahe](https://www.linkedin.com/in/killian-mah%C3%A9-246928135/) - killianmahe.pro@gmail.com

Project Link: [https://github.com/killian-mahe/lumic](https://github.com/killian-mahe/lumic)



<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
* [GitHub Emoji Cheat Sheet](https://www.webpagefx.com/tools/emoji-cheat-sheet)
* [Img Shields](https://shields.io)
* [Choose an Open Source License](https://choosealicense.com)
* [GitHub Pages](https://pages.github.com)
* [Animate.css](https://daneden.github.io/animate.css)
* [Loaders.css](https://connoratherton.com/loaders)
* [Slick Carousel](https://kenwheeler.github.io/slick)
* [Smooth Scroll](https://github.com/cferdinandi/smooth-scroll)
* [Sticky Kit](http://leafo.net/sticky-kit)
* [JVectorMap](http://jvectormap.com)
* [Font Awesome](https://fontawesome.com)





<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/othneildrew/Best-README-Template.svg?style=for-the-badge
[contributors-url]: https://github.com/othneildrew/Best-README-Template/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/othneildrew/Best-README-Template.svg?style=for-the-badge
[forks-url]: https://github.com/othneildrew/Best-README-Template/network/members
[stars-shield]: https://img.shields.io/github/stars/othneildrew/Best-README-Template.svg?style=for-the-badge
[stars-url]: https://github.com/othneildrew/Best-README-Template/stargazers
[issues-shield]: https://img.shields.io/github/issues/othneildrew/Best-README-Template.svg?style=for-the-badge
[issues-url]: https://github.com/othneildrew/Best-README-Template/issues
[license-shield]: https://img.shields.io/github/license/othneildrew/Best-README-Template.svg?style=for-the-badge
[license-url]: https://github.com/othneildrew/Best-README-Template/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/othneildrew
[product-screenshot]: images/screenshot.png
