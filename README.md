# RLAPI

RLAPI is the new RATELIMITED file hosting API, allowing for file uploading & handling in a fast and lightweight manner.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
PHP 7.x
Apache/NGINX/A PHP Capable Webserver
Minio (Can be found on minio.io)
Postgres
Our sample Database File (Will be uploaded later on. Currently working on polishing our software :) )

Also, you'll need a brain!
```

### Installing

Once you have installed all the prerequisites, clone this git repo to your working directory.

Import the database file into PostgreSQL and make sure to edit your token.

Currently, there is no configuration and everything is hardcoded, as such, please edit the files and the requirements accordingly with the file locations that you are going to have.

Once done, POST to the api with a file in the form files\[\].

Note: Do not forget to change your token in the database!

## Running the tests

Testing is fairly simple, just POST to the API and voila!


## Deployment

What's a production environment? In all seriousness, this is not production-ready and you shouldn't be trying to put it into prod

## Built With

* [PHP](http://php.net) - The web framework used
* Love - The secret ingredient

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/gtsatsis/RLAPI/tags). 

## Authors

* **George Tsatsis** - *Initial work* - [gtsatsis](https://github.com/gtsatsis)
* **Samuel Simão** - *Initial work* - [SamuelCSimao](https://github.com/SamuelCSimao)

See also the list of [contributors](https://github.com/gtsatsis/RLAPI/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details

## Acknowledgments

* Every individual StackOverflow/StackExchange Network user whose code snippets were used. They are credited in the code comments.
