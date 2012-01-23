all:
	@echo "gh2ch does not need to be compiled. Nothing to do. "
	@echo "To install, use: make install"

configure:

install:
	mkdir -p $(DESTDIR) 2>/dev/null
	cp *.php $(DESTDIR) 2>/dev/null
	cp README $(DESTDIR) 2>/dev/null
	cp -r img $(DESTDIR) 2>/dev/null
	cp -r js $(DESTDIR) 2>/dev/null
	cp -r css $(DESTDIR) 2>/dev/null
	cp gh2ch $(APACHESITES) 2>/dev/null
