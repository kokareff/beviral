
#
# Definitions
#

LIBDIR = ./lib
TESTDIR = ./test

PHPCS = phpcs
PHPCS_OPTS = -p --encoding=utf-8 --extensions=php --standard=ZottoCS

PHPUNIT = phpunit
PHPUNIT_OPTS = --colors



#
# Targets
#

.PHONY: test



all:


lint:
	${PHPCS} ${PHPCS_OPTS} ${LIBDIR}


test:
	${PHPUNIT} ${PHPUNIT_OPTS} ${TESTDIR}
