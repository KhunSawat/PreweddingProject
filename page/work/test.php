<div class="mb-3">
                        <label for="BookingH_No" class="form-label">เลือกการจอง</label>
                        <select name="BookingH_No" id="bookingH_No" class="form-select">
                            <?php while ($row = mysqli_fetch_assoc($result_booking)) : ?>
                                <option value="<?= htmlspecialchars($row['BookingH_No']) ?>"
                                    <?= $row['BookingH_No'] == $bookingH_No ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['BookingH_No']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Dressed_No" class="form-label">เลือกชุดที่เบิก</label>
                        <select name="Dressed_No" id="dressedNo" class="form-select">
                            <?php while ($dress = mysqli_fetch_assoc($result_dresses)) : ?>
                                <option value="<?= htmlspecialchars($dress['Dressed_No']) ?>"
                                    <?= $dress['Dressed_No'] == $selectedDressNo ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dress['Dressed_Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>