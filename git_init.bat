@echo on


set gitreponame=/alhadaf-local.git
set gitUsername=instance-it
set gitURL=https://github.com/
echo '------------- Git Variable Initialize'
START /WAIT git init 

echo '---- Git Initialize'
START /WAIT git add .
echo '------------------------- Git Files Added to info File'
START /WAIT git commit -m 'First Commit'
timeout 2
echo '------------------------- Git Commited'
START /WAIT git remote add origin %gitURL%%gitUsername%%gitreponame%
echo '-------------------------end Git remote add origin'
timeout 2
START /WAIT git push -u origin master
echo '-------------------------end Git Push master'

pause