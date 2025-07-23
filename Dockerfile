FROM moodlehq/moodle-php-apache:8.3

WORKDIR /var/www/html

# Clone Moodle core
RUN git clone --depth=1 -b MOODLE_500_STABLE https://github.com/moodle/moodle.git .

# Copy the plugin
COPY . /var/www/html/local/todolist

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html
