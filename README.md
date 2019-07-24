# LNPP Compose Stack

An LNPP Docker Compose stack that attempts to account for OS specific caveats such as bind mounts and file-system permissions.

```
├── app
│         ├── .docker
  |            |             |── Dockerfile
  |            |             |── Dockerfile.prod
├── srv
│         ├── .docker
  |            |             |── Dockerfile.prod
  |            |             |── default.conf
```

`docker-compose up -d` will, by default, build containers with bind mounts. There are several issues associated with file-system permissions and persistent storage when mounting volumes shared between Windows hosts and non-Windows containers.

The SMB protocol used by Windows host-mounted storage volumes do not use  the same octal code masks as Unix does, instead sets a single fixed permission which prevents groups and other users from writing to the file-system . By using bind mounts for development under Unix-like systems and named volume mounts for deployments on host operating systems that support different file-system protocols (Windows), we can support any deployment scenario with limitations. 

Modifying source files on Windows hosts will not persist changes to the mounted named volume shared between containers. Consequently, containers will need to be re-built if changes occur to the volume's file-system.


### Development (Unix only)
If an application performs operations that write to the volume's file-system, such as log storage or caching, exceptions will most likely be encountered due to the numeric user id assigned to the current host user deploying containers differing from that of the container's user writing to the volume's file-system. To account for this issue, we need to export the host's user and group id by running `./export_env`. This populates an `.env` file with environment variables we can then inject into the build context which will set the container's user id to that of the host's user id. We perform this operation for both the container user and group responsible for writing to the volume's file-system.

```
git clone …
cd ...
./export_env
docker-compose up -d
``` 

### Production
Intended for both Windows builds and production environments. Containers use named volume mounts in lieu of bind volume mounts and will need to be re-built if changes occur to the volume's file system.

```
git clone …
cd ...
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
``` 

Please note that if development containers have been previously built, production containers will need to be re-built as well due to multiple, environment specific Dockerfiles being used to circumvent the afformentioned file-system permission issues assoicated with cross-platform deployments.

```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml build
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```