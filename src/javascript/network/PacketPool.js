/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

const DataPacket = require('./DataPacket');
const Packet = require('./Packet');
const PacketSerializer = require('./serializer/PacketSerializer');
const {UnknownPacket} = require("../packets/UnknownPacket");

/**
 * Class PacketPool
 * @author Jan Sohn / xxAROX
 * @date 01.07.2022 - 17:28
 * @project Protocol
 */
class PacketPool {
	/** @private */
	static instance = null;
	pool = {};

	static getInstance() {
		if (PacketPool.instance === null) PacketPool.instance = new PacketPool();
		return PacketPool.instance;
	}

	constructor() {
		if (PacketPool.instance !== null) throw new Error("PacketPool is a singleton class");
		this.pool = {};

		this.register(new UnknownPacket);
	}

	/**
	 * @param {CloudPacket} packet
	 */
	register(packet) {
		this.pool[packet.getType()] = Object.clone(packet);
	}

	getById(packet_id) {
		return this.pool[packet_id] || undefined;
	}

	getByName(packet_name) {
		for (let packet_id in this.pool) {
			if (this.pool[packet_id].getName().toLowerCase() === packet_name.toLowerCase()) return this.pool[packet_id];
		}
		return undefined;
	}

	/**
	 * @param {Buffer} buffer
	 */
	get(buffer) {
		let serializer = new PacketSerializer(buffer.toString(), 0);
		serializer.decompress();
		return this.getById(serializer.readUnsignedVarInt() & DataPacket.PID_MASK);
	}
}
module.exports = PacketPool;
