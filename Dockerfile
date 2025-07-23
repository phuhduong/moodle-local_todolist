FROM moodlehq/moodle-php-apache:8.3

# Clone Moodle
RUN git clone --depth=1 -b MOODLE_500_STABLE https://github.com/moodle/moodle.git /var/www/html

# Copy plugin
COPY . /var/www/html/local/todolist
