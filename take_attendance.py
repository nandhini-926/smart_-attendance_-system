import face_recognition
import cv2
import os
import pickle
import mysql.connector
from datetime import datetime
import time

# Constants
ENCODINGS_FILE = "encodings.pickle"
IMAGE_DIR = "images"
NUM_CLASSES = 7
CAPTURE_GAP_SECONDS = 5

# Load encodings
print("[INFO] Loading known face encodings...")
with open(ENCODINGS_FILE, "rb") as f:
    data = pickle.load(f)

# Setup DB connection
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="newpassword",  # Update if needed
    database="attendance"
)
cursor = conn.cursor()

# Ensure image directory exists
os.makedirs(IMAGE_DIR, exist_ok=True)

# Open webcam once
cap = cv2.VideoCapture(0)
if not cap.isOpened():
    print("[ERROR] Could not access the webcam.")
    exit()

print("[INFO] Starting attendance for 7 classes with 5s gap...")

for class_num in range(1, NUM_CLASSES + 1):
    print(f"[INFO] Capturing for Class {class_num}...")

    # Give time for camera to adjust
    time.sleep(CAPTURE_GAP_SECONDS)

    ret, frame = cap.read()
    if not ret:
        print("[ERROR] Failed to grab frame.")
        continue

    rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    face_locations = face_recognition.face_locations(rgb_frame)
    face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

    for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
        matches = face_recognition.compare_faces(data["encodings"], face_encoding)
        name = "Unknown"
        color = (0, 0, 255)

        if True in matches:
            matchedIdxs = [i for i, b in enumerate(matches) if b]
            counts = {}
            for i in matchedIdxs:
                matched_name = data["names"][i]
                counts[matched_name] = counts.get(matched_name, 0) + 1
            name = max(counts, key=counts.get)
            color = (0, 255, 0)

            # Get roll_no from DB
            cursor.execute("SELECT roll_no FROM students WHERE name = %s", (name,))
            result = cursor.fetchone()

            if result:
                roll_no = result[0]
                timestamp = datetime.now()
                date = timestamp.date()
                image_path = os.path.join(IMAGE_DIR, f"{name}{timestamp.strftime('%Y%m%d%H%M%S')}.jpg")
                cv2.imwrite(image_path, frame)

                # Check for duplicate attendance in same class slot (based on timestamp range if needed)
                cursor.execute("""
                    SELECT COUNT(*) FROM attendance_records 
                    WHERE roll_no = %s AND DATE(timestamp) = %s AND HOUR(timestamp) = %s
                """, (roll_no, date, timestamp.hour))
                already_marked = cursor.fetchone()[0]

                if already_marked == 0:
                    cursor.execute("""
                        INSERT INTO attendance_records (roll_no, name, timestamp, image_path, date, status)
                        VALUES (%s, %s, %s, %s, %s, %s)
                    """, (roll_no, name, timestamp, image_path, date, "Present"))

                    # Update attendance in students table
                    cursor.execute("""
                        UPDATE students 
                        SET 
                            attended_classes = attended_classes + 1,
                            week_attended_classes = week_attended_classes + 1
                        WHERE roll_no = %s
                    """, (roll_no,))
                    print(f"[MARKED] Attendance for {name} (Class {class_num})")
                else:
                    print(f"[SKIPPED] {name} already marked for this hour.")
            else:
                print(f"[ERROR] No roll_no found for {name}")
        else:
            print("[INFO] Unknown face detected.")

        # Draw face box
        cv2.rectangle(frame, (left, top), (right, bottom), color, 2)
        cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.75, color, 2)

    # Show captured frame
    cv2.imshow(f"Class {class_num} Attendance", frame)
    cv2.waitKey(1000)
    cv2.destroyAllWindows()

    # Increment total_classes for all students (once per class)
    cursor.execute("UPDATE students SET total_classes = total_classes + 1, week_total_classes = week_total_classes + 1")
    conn.commit()

# Cleanup
cap.release()
cv2.destroyAllWindows()
cursor.close()
conn.close()
print("[INFO] Attendance captured for all 7 classes successfully.")