del vine.js

for %%x in (js\*.js*) do (
    type %%~x >> vine.tmp
    echo. >> vine.tmp
    echo. >> vine.tmp
)

ren vine.tmp vine.js

#for %%a in (vine.js) do (
#    @java -jar C:\Utilities\yuicompressor.jar "%%a" -o "%%a"
#)