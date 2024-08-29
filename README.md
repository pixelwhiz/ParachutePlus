# ParachutePlus

**ParachutePlus** is a Minecraft PocketMine plugin that allows players to use parachutes for a fun and safe descent. The plugin introduces two custom items: Parachute and AutoParachute, providing unique gameplay mechanics such as deploying a parachute and automatically activating it when falling.

## Features

- **Parachute Item**: Players can obtain a parachute item, which they can use to spawn a parachute entity (represented by a chicken).
- **Auto Parachute**: Automatically deploys the parachute when needed, providing a smooth and controlled descent.
- **Custom Entity**: The parachute is represented by a custom `Chicken` entity with modified behavior and properties.
- **Cooldown System**: Prevents players from spamming the parachute item with a configurable cooldown time.
- **Automatic Despawn**: The parachute entity automatically despawns after landing.

## Commands

- **/parachute give <player name>**: Gives a parachute item to the specified player.

### Permissions

- `parachuteplus.cmd`: Allows a user to execute the parachute-related commands.

## Installation

1. Download the `ParachutePlus` plugin `.phar` file.
2. Place the `.phar` file into the `plugins` folder of your PocketMine-MP server.
3. Restart the server or load the plugin using the `/reload` command.

## Usage

1. **Give Parachute**: Use the `/parachute give <player name>` command to give a parachute to a player.
2. **Deploy Parachute**: Right-click with the parachute item in hand to deploy the parachute. The player will be mounted on a custom chicken entity, which will glide the player safely downwards.
3. **Auto Parachute**: Once deployed, the parachute item turns into an `AutoParachute`, which automatically deploys upon falling. It also removes the parachute when the player lands.

## Contributing

Contributions are welcome! Feel free to submit [Issue](https://github.com/pixelwhiz/ParachutePlus/issues/new), feature requests, and pull requests.

## License

This plugin is licensed under the MIT License. See the [LICENSE](https://github.com/pixelwhiz/ParachutePlus/blob/master/LICENSE) file for more information.
