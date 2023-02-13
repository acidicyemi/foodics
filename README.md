
# FOODICS

This is Foodics test application. Note: This is not a fully production ready application.

## Postman Documentation

[Documentation](https://documenter.getpostman.com/view/5487875/2s935uH1oD)


## Environment Variables

To run this project,you will need docker up and running. clean up command are added to remove all created volume and network



## Run Locally

#### Clone the project

```bash
  git clone https://link-to-project
```

#### Go to the project directory

```bash
  cd my-project
```

#### Create the required docker setup

```bash
  make setup_docker
```

#### Copy env to_path

```bash
  make copy_env
```

#### Note:
> once the above command is ran  update your mail credentials,
you can use mail trap or your prefered mail service

```MAIL_MAILER=smtp```

```MAIL_HOST=sandbox.smtp.mailtrap.io```

```MAIL_PORT=2525```

```MAIL_USERNAME=*******```

```MAIL_PASSWORD=********```

```MAIL_ENCRYPTION=tls```

```MAIL_FROM_ADDRESS=admin@test.com```

```MAIL_FROM_NAME="${APP_NAME}"```

```# Also you can update the values with your preferred values```

```WEBSERVER_PORT=4444```

```DB_PORT_OUTSIDE=4445```

```APP_PORT=4446```

```MYSQL_PASSWORD=password```


#### Start APP
```bash
  make start
```

#### Install Packages
```bash
  make install_packages
```

#### Generate APP_KEY
```bash
  make generate_key
```

#### Run Migration
```bash
  make migrate
```

#### Run seeder
```bash
  make seed
```

#### Run test
```bash
  make run_test
```

### Download collection to run apis

#### To access db terminal

```bash
  make db_bash
```

#### To access app terminal

```bash
  make php_bash
```

#### To cleanup and delete all docker data

```bash
  make cleanup
```
## Authors

- [@acidicyemi](https://www.github.com/acidicyemi)

