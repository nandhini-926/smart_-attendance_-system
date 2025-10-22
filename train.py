import os
import face_recognition
import cv2
import pickle

DATASET_PATH = "ds_students"
known_encodings = []
known_names = []

def preprocess_image(image_path):
    image = cv2.imread(image_path)
    if image is None:
        return None

    image = cv2.resize(image, (500, 500))  # Resize for consistency
    rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)  # Convert to RGB
    return rgb

for student in os.listdir(DATASET_PATH):
    student_dir = os.path.join(DATASET_PATH, student)
    if not os.path.isdir(student_dir):
        continue

    for filename in os.listdir(student_dir):
        if filename.lower().endswith((".jpg", ".png")):
            image_path = os.path.join(student_dir, filename)
            rgb_image = preprocess_image(image_path)
            if rgb_image is None:
                continue

            boxes = face_recognition.face_locations(rgb_image)
            encodings = face_recognition.face_encodings(rgb_image, boxes)

            for encoding in encodings:
                known_encodings.append(encoding)
                known_names.append(student)
                print(f"[ENCODED] {student}")

# Save encodings
data = {"encodings": known_encodings, "names": known_names}
with open("encodings.pickle", "wb") as f:
    pickle.dump(data, f)

print("[INFO] Training completed. Encodings saved.")
