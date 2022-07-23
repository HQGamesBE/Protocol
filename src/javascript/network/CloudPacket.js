/**
 * Class CloudPacket
 * @package
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 17:24
 * @ide PhpStorm
 * @project Protocol
 */
const {Packets} = require("./Packets");
class CloudPacket {
	static type = "Unknown";
	identifier = undefined;

	/**
	 * @return {CloudPacket}
	 */
	static build = () => {
		const self = new CloudPacket;
		// ...
		return self;
	};

	getType() {
		return this.constructor.type;
	}

	jsonSerialize() {
		let data = {
			type: this.getType(),
		};
		// add all properties to data
		for (let property in this) {
			if (this.hasOwnProperty(property)) {
				if (
					property !== "type"
					&& property !== "identifier"
					&& property !== "constructor"
					&& property !== "jsonSerialize"
					&& property !== "getType"
					&& property !== "build"
				) {
					data[ property ] = this[ property ];
				}
			}
		}
		return data;
	}
}
module.exports = CloudPacket;