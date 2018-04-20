FILES = levelOrder preOrder inOrder postOrder components isTree findBridges articulation
make:
	$(foreach file,$(FILES),echo -e "php src/$(file).php \x24\x40" > $(file); chmod 777 $(file);)
clean:
	rm $(foreach file,$(FILES),$(file))
