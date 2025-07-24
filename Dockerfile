FROM moodlehq/moodle-php-apache:8.3

WORKDIR /var/www/html

# Clone Moodle core
RUN git clone --depth=1 -b MOODLE_500_STABLE https://github.com/moodle/moodle.git .

# Install PHPUnit
RUN curl -Ls https://phar.phpunit.de/phpunit-9.phar -o /usr/local/bin/phpunit \
  && chmod +x /usr/local/bin/phpunit

# Copy the plugin
COPY . /var/www/html/local/todolist
