all:
	@echo "gh2ch does not need to be compiled. Nothing to do. "
	@echo "To install, use: make install"

configure:

install:
	mkdir -p $(DESTDIR) 2>/dev/null
	cp -r * $(DESTDIR);
